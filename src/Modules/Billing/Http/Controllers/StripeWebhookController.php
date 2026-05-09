<?php

namespace BilliftySDK\SharedResources\Modules\Billing\Http\Controllers;

use App\Http\Controllers\Controller;
use BilliftySDK\SharedResources\Modules\Billing\Contracts\PaymentGateway;
use BilliftySDK\SharedResources\Modules\Billing\Models\StripeWebhookEvents;
use BilliftySDK\SharedResources\Modules\Billing\Models\UserSubscription;
use BilliftySDK\SharedResources\Modules\Invoicing\Models\Invoices;
use BilliftySDK\SharedResources\Modules\User\Models\Plan;
use BilliftySDK\SharedResources\Modules\User\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Stripe\Exception\SignatureVerificationException;
use Stripe\StripeClient;
use Stripe\Webhook;

class StripeWebhookController extends Controller
{
	public function __construct(private StripeClient $stripe)
	{
	}

	public function handle(Request $request, PaymentGateway $gateway)
	{
		// ---------------------------------------------------------------------
		// Basic hit logging (helps you confirm the request actually reached Laravel)
		// ---------------------------------------------------------------------
		Log::info('StripeWebhookController.hit', [
			'path' => $request->path(),
			'host' => $request->getHost(),
			'scheme' => $request->getScheme(),
			'has_sig' => $request->hasHeader('Stripe-Signature'),
		]);

		$payload = $request->getContent();
		$sigHeader = $request->header('Stripe-Signature');
		$secret = config('services.stripe.webhook_secret');

		if (!$secret) {
			Log::error('StripeWebhookController.missing_webhook_secret');
			return response('Webhook secret missing', 500);
		}

		try {
			$event = Webhook::constructEvent($payload, $sigHeader, $secret);
		} catch (\UnexpectedValueException $e) {
			Log::warning('StripeWebhookController.invalid_payload', ['err' => $e->getMessage()]);
			return response('Invalid payload', 400);
		} catch (SignatureVerificationException $e) {
			Log::warning('StripeWebhookController.invalid_signature', [
				'err' => $e->getMessage(),
				'secret_prefix' => substr($secret, 0, 10) . '...',
			]);
			return response('Invalid signature', 400);
		}

		Log::info('StripeWebhookController.event_ok', [
			'id' => $event->id,
			'type' => $event->type,
			'livemode' => (bool)($event->livemode ?? false),
		]);

		// Store raw payload (useful for debugging later)
		$payloadArray = json_decode($payload, true);
		$payloadJson = is_array($payloadArray)
			? json_encode($payloadArray, JSON_UNESCAPED_SLASHES)
			: json_encode(['raw' => $payload], JSON_UNESCAPED_SLASHES);

		$object = $event->data->object ?? null;

		// Extract customer/subscription ids if present on object
		$stripeCustomerId = $object->customer ?? null;
		$stripeSubscriptionId = $object->subscription ?? ($object->id ?? null);

		// Try to resolve user id from metadata/customer/subscription
		$userId = $this->resolveUserIdFromEvent($event, $stripeCustomerId, $stripeSubscriptionId);

		// Persist event record (idempotent)
		StripeWebhookEvents::updateOrCreate(
			['event_id' => $event->id],
			[
				'user_id' => $userId,
				'type' => $event->type,
				'api_version' => $event->api_version ?? null,
				'livemode' => (bool)($event->livemode ?? false),
				'stripe_customer_id' => $stripeCustomerId,
				'stripe_subscription_id' => $stripeSubscriptionId,
				'payload' => $payloadJson,
				'received_at' => now(),
			]
		);

		// Only sync on the important events
		$shouldSync = in_array($event->type, [
			'checkout.session.completed',
			'customer.subscription.created',
			'customer.subscription.updated',
			'customer.subscription.deleted',
			'invoice.payment_succeeded',
			'invoice.payment_failed',
			'checkout.session.async_payment_succeeded',
			'checkout.session.async_payment_failed'
		], true);

		if (!$shouldSync) {
			return response('OK', 200);
		}

		// ----------------------------------------------------------------------------
		// If this Checkout Session belongs to an invoice, handle invoice payment.
		// If it does not have invoice_id metadata, continue to subscription logic.
		// ----------------------------------------------------------------------------
		if (in_array($event->type, [
			'checkout.session.completed',
			'checkout.session.async_payment_succeeded',
			'checkout.session.async_payment_failed',
		])) {
			$session = $event?->data?->object ?? null;

			$invoiceId = $session?->metadata?->invoice_id ?? null;

			if ($invoiceId) {
				if ($event->type === 'checkout.session.async_payment_failed') {
					$this->markInvoicePaymentFailed($session);
				} else {
					$this->markInvoicePaid($session);
				}

				return response('OK', 200);
			}
		}

		// Determine subscription ID depending on event type
		$subId = null;
		$sessionMeta = [];

		if ($event->type === 'checkout.session.completed') {
			$session = $event->data->object;
			$subId = $session->subscription ?? null;
			$stripeCustomerId = $session->customer ?? $stripeCustomerId;
			$sessionMeta = (array)($session->metadata ?? []);
		} elseif (str_starts_with($event->type, 'customer.subscription.')) {
			$sub = $event->data->object;
			$subId = $sub->id ?? null;
			$stripeCustomerId = $sub->customer ?? $stripeCustomerId;
		} elseif (str_starts_with($event->type, 'invoice.')) {
			$invoice = $event->data->object;
			$subId = $invoice->subscription ?? null;
			$stripeCustomerId = $invoice->customer ?? $stripeCustomerId;
		}

		if (!$subId) {
			Log::warning('StripeWebhookController.no_subscription_id_to_sync', ['event_type' => $event->type]);
			return response('OK', 200);
		}

		$cancelsAt = null;
		$cancelAtPeriodEnd = null;
		// Retrieve canonical subscription from Stripe (most reliable)
		try {
			$subscription = $this->stripe->subscriptions->retrieve($subId, [
				'expand' => ['items.data.price'],
			]);
		} catch (\Throwable $e) {
			Log::error('StripeWebhookController.subscription_retrieve_failed', [
				'subscription_id' => $subId,
				'err' => $e->getMessage(),
			]);
			return response('OK', 200);
		}

		if ($event->type === 'customer.subscription.deleted') {
			$gateway->markUserAsFree($userId, $stripeCustomerId, $subId, $payloadJson);
			return response('OK', 200);
		}

		$cancelAtPeriodEnd = (bool)($subscription->cancel_at_period_end ?? false);
		$cancelAtTs = $subscription->cancel_at ?? null;
		$currentPeriodEndTs = $subscription->current_period_end ?? null;

		// Stripe can schedule cancel either via cancel_at OR cancel_at_period_end+current_period_end
		if ($cancelAtTs) {
			$cancelsAt = Carbon::createFromTimestampUTC((int)$cancelAtTs);
		} elseif ($cancelAtPeriodEnd && $currentPeriodEndTs) {
			$cancelsAt = Carbon::createFromTimestampUTC((int)$currentPeriodEndTs);
		} else {
			// user might have "uncanceled" - clear cancels_at
			$cancelsAt = null;
		}


		// Resolve user fallback by customer
		if (!$userId && $stripeCustomerId) {
			$userId = DB::table('users')->where('stripe_customer_id', $stripeCustomerId)->value('id');
		}

		if (!$userId) {
			Log::warning('StripeWebhookController.cannot_resolve_user', [
				'subscription_id' => $subId,
				'customer' => $stripeCustomerId,
			]);
			return response('OK', 200);
		}

		// Plan + cycle from metadata, fallback to priceId map
		$subMeta = (array)($subscription->metadata ?? []);
		$planCode = strtolower((string)($subMeta['plan_code'] ?? $sessionMeta['plan_code'] ?? ''));
		$billingCycle = strtolower((string)($subMeta['billing_cycle'] ?? $sessionMeta['billing_cycle'] ?? ''));

		$stripeItem = $subscription->items->data[0] ?? null;
		$stripePrice = $stripeItem?->price ?? null;
		$priceId = $stripePrice->id ?? null;

		if ((!$planCode || !$billingCycle) && $priceId) {
			$map = $this->priceIdToPlanAndCycle($priceId);
			if ($map) {
				$planCode = $planCode ?: $map['plan_code'];
				$billingCycle = $billingCycle ?: $map['billing_cycle'];
			}
		}

		$planId = $planCode ? Plan::where('code', $planCode)->value('id') : null;
		$freeId = Plan::where('code', 'free')->value('id');

		// FIX: store these in UTC from Stripe timestamps
		$startsAt = isset($subscription->current_period_start)
			? Carbon::createFromTimestampUTC((int)$subscription->current_period_start)
			: null;
		$renewsAt = isset($subscription->current_period_end)
			? Carbon::createFromTimestampUTC((int)$subscription->current_period_end)
			: null;

		// Upsert subscription
		UserSubscription::updateOrCreate(
			[
				// 'stripe_subscription_id' => $subscription->id
				'user_id' => (int)$userId,
			],
			[
				'stripe_subscription_id' => $subscription->id,
				'plan_id' => $planId ?? $freeId,
				'plan_code' => $planCode ?: 'free',
				'billing_cycle' => $billingCycle ?: 'monthly',
				'stripe_customer_id' => (string)$stripeCustomerId,
				'currency' => $stripePrice->currency ?? 'usd',
				'unit_amount' => $stripePrice->unit_amount ?? 0,
				'status' => $subscription->status ?? 'incomplete',
				'starts_at' => $startsAt,
				'renews_at' => $renewsAt,
				'raw_payload' => $subscription->toArray(),
				'cancels_at' => $cancelsAt,
				'canceled_at' => null,
			]
		);

		// Only grant paid access if active/trialing
		$isPaid = in_array((string)($subscription->status ?? ''), ['active', 'trialing'], true);

		$user = User::find($userId);
		if ($user) {
			$user->forceFill([
				'plan_id' => ($isPaid && $planId) ? $planId : $freeId,
			])->save();
		}

		return response('OK', 200);
	}

	private function priceIdToPlanAndCycle(string $priceId): ?array
	{
		$prices = config('services.stripe.prices', []);

		foreach (['pro', 'premium'] as $plan) {
			foreach (['monthly', 'yearly'] as $cycle) {
				$cfg = $prices[$plan][$cycle] ?? null;
				if ($cfg && $cfg === $priceId) {
					return ['plan_code' => $plan, 'billing_cycle' => $cycle];
				}
			}
		}
		return null;
	}

	private function resolveUserIdFromEvent($event, ?string $stripeCustomerId, ?string $stripeSubscriptionId): ?int
	{
		$object = $event->data->object ?? null;

		// Metadata user id (works for checkout session + subscription if you set it)
		$metaUserId = $object->metadata->billifty_user_id ?? null;
		if ($metaUserId) return (int)$metaUserId;

		// If we already have the subscription id, map to existing row
		if ($stripeSubscriptionId) {
			$id = DB::table('user_subscriptions')
				->where('stripe_subscription_id', $stripeSubscriptionId)
				->value('user_id');
			if ($id) return (int)$id;
		}

		// Fallback: user by stripe customer id
		if ($stripeCustomerId) {
			$id = DB::table('users')
				->where('stripe_customer_id', $stripeCustomerId)
				->value('id');
			if ($id) return (int)$id;
		}

		return null;
	}

	private function markInvoicePaid(object $session): void
	{
		$invoiceId = $session->metadata->invoice_id ?? null;

		if (!$invoiceId) {
			Log::warning('StripeWebhookController.invoice_payment_missing_invoice_id', [
				'session_id' => $session->id ?? null,
			]);

			return;
		}

		$invoice = Invoices::query()->find($invoiceId);

		if (!$invoice) {
			Log::warning('StripeWebhookController.invoice_not_found', [
				'invoice_id' => $invoiceId,
				'session_id' => $session->id ?? null,
			]);

			return;
		}

		$invoice->update([
			'status' => 'paid',
			'paid_at' => now(),
		]);
	}

	private function markInvoicePaymentFailed(object $session): void
	{
		$invoiceId = $session->metadata->invoice_id ?? null;

		if (!$invoiceId) {
			Log::warning('StripeWebhookController.invoice_payment_failed_missing_invoice_id', [
				'session_id' => $session->id ?? null,
			]);

			return;
		}

		$invoice = Invoices::query()->find($invoiceId);

		if (!$invoice) {
			Log::warning('StripeWebhookController.invoice_not_found_for_failed_payment', [
				'invoice_id' => $invoiceId,
				'session_id' => $session->id ?? null,
			]);

			return;
		}

		$invoice->update([
			'status' => 'void',
		]);
	}
}
