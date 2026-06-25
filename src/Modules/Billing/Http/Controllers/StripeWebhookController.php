<?php

namespace BilliftySDK\SharedResources\Modules\Billing\Http\Controllers;

use App\Http\Controllers\Controller;
use BilliftySDK\SharedResources\Modules\Billing\Contracts\PaymentGateway;
use BilliftySDK\SharedResources\Modules\Billing\Http\Controllers\Traits\StripeBillable;
use BilliftySDK\SharedResources\Modules\Billing\Mail\PaymentSuccessNotificationForBusinessProfileMail;
use BilliftySDK\SharedResources\Modules\Billing\Mail\PaymentSuccessNotificationForClientMail;
use BilliftySDK\SharedResources\Modules\Billing\Models\PaymentRecord;
use BilliftySDK\SharedResources\Modules\Billing\Models\StripeWebhookEvents;
use BilliftySDK\SharedResources\Modules\Billing\Models\UserSubscription;
use BilliftySDK\SharedResources\Modules\Billing\Services\PlanUsageService;
use BilliftySDK\SharedResources\Modules\Billing\Support\StripePriceResolver;
use BilliftySDK\SharedResources\Modules\Invoicing\Models\Currency;
use BilliftySDK\SharedResources\Modules\Invoicing\Models\Invoices;
use BilliftySDK\SharedResources\Modules\Invoicing\Services\Reminders\InvoicePaymentReminderService;
use BilliftySDK\SharedResources\Modules\User\Models\Plan;
use BilliftySDK\SharedResources\Modules\User\Models\User;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Stripe\Exception\SignatureVerificationException;
use Stripe\StripeClient;
use Stripe\Webhook;

class StripeWebhookController extends Controller
{
	use StripeBillable;

    public function __construct(
		private StripeClient $stripe,
		private InvoicePaymentReminderService $paymentReminderService,
		private ?PlanUsageService $planUsageService = null,
	) {}

    public function handle(Request $request, PaymentGateway $gateway)
    {
        Log::info('StripeWebhookController.hit', [
            'path' => $request->path(),
            'host' => $request->getHost(),
            'scheme' => $request->getScheme(),
            'has_sig' => $request->hasHeader('Stripe-Signature'),
        ]);

        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $secrets = array_values(array_unique(array_filter(array_map(
            fn ($secret) => is_string($secret) ? trim($secret) : null,
            [
                config('services.stripe.webhook_secret'),
                config('services.stripe.connect_webhook_secret'),
            ]
        ))));

        if (empty($secrets)) {
            Log::error('StripeWebhookController.missing_webhook_secret');

            return response('Webhook secret missing', 500);
        }

        try {
            $event = $this->constructEventWithConfiguredSecrets($payload, $sigHeader, $secrets);
        } catch (\UnexpectedValueException $e) {
            Log::warning('StripeWebhookController.invalid_payload', ['err' => $e->getMessage()]);

            return response('Invalid payload', 400);
        } catch (SignatureVerificationException $e) {
            Log::warning('StripeWebhookController.invalid_signature', [
                'err' => $e->getMessage(),
                'configured_secret_count' => count($secrets),
            ]);

            return response('Invalid signature', 400);
        }

        Log::info('StripeWebhookController.event_ok', [
            'id' => $event->id,
            'type' => $event->type,
            'livemode' => (bool) ($event->livemode ?? false),
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

        try {
            StripeWebhookEvents::create([
                'event_id' => $event->id,
                'user_id' => $userId,
                'type' => $event->type,
                'api_version' => $event->api_version ?? null,
                'livemode' => (bool) ($event->livemode ?? false),
                'stripe_customer_id' => $stripeCustomerId,
                'stripe_subscription_id' => $stripeSubscriptionId,
                'payload' => $payloadJson,
                'received_at' => now(),
            ]);
        } catch (QueryException $e) {
            if ($this->isDuplicateEventException($e)) {
                Log::info('StripeWebhookController.duplicate_event_skipped', [
                    'event_id' => $event->id,
                    'event_type' => $event->type,
                ]);

                return response('OK', 200);
            }

            Log::error('StripeWebhookController.event_persist_failed', [
                'event_id' => $event->id,
                'event_type' => $event->type,
                'err' => $e->getMessage(),
            ]);

            return response('Webhook event persist failed', 500);
        }

        try {
            return $this->processVerifiedEvent(
                $event,
                $payloadJson,
                $gateway,
                $userId,
                $stripeCustomerId,
                $stripeSubscriptionId
            );
        } catch (\Throwable $e) {
            StripeWebhookEvents::query()->where('event_id', $event->id)->delete();

            Log::error('StripeWebhookController.processing_failed', [
                'event_id' => $event->id,
                'event_type' => $event->type,
                'err' => $e->getMessage(),
            ]);

            return response('Webhook processing failed', 500);
        }
    }

    private function processVerifiedEvent(
        object $event,
        string $payloadJson,
        PaymentGateway $gateway,
        ?int $userId,
        ?string $stripeCustomerId,
        ?string $stripeSubscriptionId
    ) {
        $shouldSync = in_array($event->type, [
            'checkout.session.completed',
            'checkout.session.expired',
            'customer.subscription.created',
            'customer.subscription.updated',
            'customer.subscription.deleted',
			'invoice.paid',
            'invoice.payment_succeeded',
            'invoice.payment_failed',
            'checkout.session.async_payment_succeeded',
            'checkout.session.async_payment_failed',
        ], true);

        if (! $shouldSync) {
            return response('OK', 200);
        }

        if (in_array($event->type, [
            'checkout.session.async_payment_failed',
            'checkout.session.expired',
        ], true)) {
            $session = $event?->data?->object ?? null;
            $invoiceId = $session?->metadata?->invoice_id ?? null;

            if ($invoiceId) {
                $this->processFailedInvoicePayment($event, $session, (int) $invoiceId);

                return response('OK', 200);
            }

            Log::warning('StripeWebhookController.invoice_checkout_failure_missing_invoice_id', [
                'event_id' => $event->id,
                'event_type' => $event->type,
                'session_id' => $session?->id ?? null,
                'payment_status' => $session?->payment_status ?? null,
                'metadata' => (array) ($session?->metadata ?? []),
                'account' => $event->account ?? null,
            ]);
        }

        if (in_array($event->type, [
            'checkout.session.completed',
            'checkout.session.async_payment_succeeded',
        ], true)) {
            $session = $event?->data?->object ?? null;

            $invoiceId = $session?->metadata?->invoice_id ?? null;

            if ($invoiceId) {
                Log::info('StripeWebhookController.invoice_checkout_received', [
                    'event_id' => $event->id,
                    'event_type' => $event->type,
                    'session_id' => $session?->id ?? null,
                    'invoice_id' => $invoiceId,
                    'account' => $event->account ?? null,
                ]);

                $this->processSuccessPayment($event, $session, $invoiceId);

                return response('OK', 200);
            }

            Log::warning('StripeWebhookController.invoice_checkout_missing_invoice_id', [
                'event_id' => $event->id,
                'event_type' => $event->type,
                'session_id' => $session?->id ?? null,
                'payment_status' => $session?->payment_status ?? null,
                'metadata' => (array) ($session?->metadata ?? []),
                'account' => $event->account ?? null,
            ]);
        }

        // Determine subscription ID depending on event type
        $subId = null;
        $sessionMeta = [];

        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;
            $subId = $session->subscription ?? null;
            $stripeCustomerId = $session->customer ?? $stripeCustomerId;
            $sessionMeta = (array) ($session->metadata ?? []);
        } elseif (str_starts_with($event->type, 'customer.subscription.')) {
            $sub = $event->data->object;
            $subId = $sub->id ?? null;
            $stripeCustomerId = $sub->customer ?? $stripeCustomerId;
        } elseif (str_starts_with($event->type, 'invoice.')) {
            $invoice = $event->data->object;

            $subId = $invoice->subscription
                ?? $invoice->parent?->subscription_details?->subscription
                ?? null;

            $stripeCustomerId = $invoice->customer ?? $stripeCustomerId;
        }

        if (! $subId) {
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
            UserSubscription::query()
                ->when($userId, fn ($query) => $query->where('user_id', (int) $userId))
                ->when(! $userId, fn ($query) => $query->where('stripe_subscription_id', $subId))
                ->update(['renews_at' => null]);

            return response('OK', 200);
        }

        $cancelAtPeriodEnd = (bool) ($subscription->cancel_at_period_end ?? false);
        $cancelAtTs = $subscription->cancel_at ?? null;
        $currentPeriodEnd = $this->resolveSubscriptionPeriodEnd($subscription);

        // Stripe can schedule cancel either via cancel_at OR cancel_at_period_end+current_period_end
        if ($cancelAtTs) {
            $cancelsAt = Carbon::createFromTimestampUTC((int) $cancelAtTs);
        } elseif ($cancelAtPeriodEnd && $currentPeriodEnd) {
            $cancelsAt = $currentPeriodEnd;
        } else {
            // user might have "uncanceled" - clear cancels_at
            $cancelsAt = null;
        }

        // Resolve user fallback by customer
        if (! $userId && $stripeCustomerId) {
            $userId = DB::table('users')->where('stripe_customer_id', $stripeCustomerId)->value('id');
        }

        if (! $userId) {
            Log::warning('StripeWebhookController.cannot_resolve_user', [
                'subscription_id' => $subId,
                'customer' => $stripeCustomerId,
            ]);

            return response('OK', 200);
        }

        // Plan + cycle from metadata, fallback to priceId map
        $subMeta = (array) ($subscription->metadata ?? []);
        $planCode = strtolower((string) ($subMeta['plan_code'] ?? $sessionMeta['plan_code'] ?? ''));
        $billingCycle = strtolower((string) ($subMeta['billing_cycle'] ?? $sessionMeta['billing_cycle'] ?? ''));

        $stripeItem = $subscription->items->data[0] ?? null;
        $stripePrice = $stripeItem?->price ?? null;
        $priceId = $stripePrice->id ?? null;

        if ((! $planCode || ! $billingCycle) && $priceId) {
            $map = $this->priceIdToPlanAndCycle($priceId);
            if ($map) {
                $planCode = $planCode ?: $map['plan_code'];
                $billingCycle = $billingCycle ?: $map['billing_cycle'];
            }
        }

        $planId = $planCode ? Plan::where('code', $planCode)->value('id') : null;
        $freeId = Plan::where('code', 'free')->value('id');

        // FIX: store these in UTC from Stripe timestamps
        $startsAt = $this->resolveSubscriptionPeriodStart($subscription);
        $renewsAt = $this->resolveSubscriptionPeriodEnd($subscription);

        $status = (string) ($subscription->status ?? 'incomplete');
        $isPaid = in_array($status, ['active', 'trialing'], true);
        $existingSubscription = UserSubscription::query()
            ->where('user_id', (int) $userId)
            ->first();

        $preserveCurrentPlan = ! $isPaid && $existingSubscription;
        $effectivePlanId = $preserveCurrentPlan
            ? $existingSubscription->plan_id
            : (($isPaid && $planId) ? $planId : $freeId);
        $effectivePlanCode = $preserveCurrentPlan
            ? $existingSubscription->plan_code
            : (($isPaid && $planCode) ? $planCode : 'free');
        $effectiveBillingCycle = $preserveCurrentPlan
            ? $existingSubscription->billing_cycle
            : (($isPaid && $billingCycle) ? $billingCycle : 'monthly');
        $effectiveUnitAmount = $preserveCurrentPlan
            ? $existingSubscription->unit_amount
            : ($isPaid ? ($stripePrice->unit_amount ?? 0) : 0);

        $canceledAt = null;
        if (! $isPaid && ! empty($subscription->canceled_at)) {
            $canceledAt = Carbon::createFromTimestampUTC((int) $subscription->canceled_at);
        }

        // Upsert subscription. Failed/unpaid updates preserve the existing local plan
        // while keeping Stripe IDs for future recovery webhooks.
        $subscriptionModel = UserSubscription::updateOrCreate(
            [
                // 'stripe_subscription_id' => $subscription->id
                'user_id' => (int) $userId,
            ],
            [
                'stripe_subscription_id' => $subscription->id,
                'plan_id' => $effectivePlanId,
                'plan_code' => $effectivePlanCode,
                'billing_cycle' => $effectiveBillingCycle,
                'stripe_customer_id' => (string) $stripeCustomerId,
                'currency' => $stripePrice->currency ?? 'usd',
                'unit_amount' => $effectiveUnitAmount,
                'status' => $status,
                'starts_at' => $startsAt,
                'renews_at' => $cancelsAt ? null : $renewsAt,
                'raw_payload' => $subscription->toArray(),
                'cancels_at' => $cancelsAt,
                'canceled_at' => $canceledAt,
            ]
        );

        $user = User::find($userId);
        if ($user) {
            $user->forceFill([
                'plan_id' => $effectivePlanId,
            ])->save();

			$this->syncPlanUsagePeriodsIfAvailable($user->refresh(), $subscriptionModel);
        }

        return response('OK', 200);
    }

    private function isDuplicateEventException(QueryException $e): bool
    {
        $sqlState = (string) ($e->errorInfo[0] ?? '');
        $driverCode = (string) ($e->errorInfo[1] ?? $e->getCode());
        $message = strtolower($e->getMessage());

        return $sqlState === '23505'
            || in_array($driverCode, ['1062', '2067'], true)
            || (($sqlState === '23000' || $driverCode === '19')
                && (str_contains($message, 'duplicate') || str_contains($message, 'unique')));
    }

	private function planUsageService(): PlanUsageService
	{
		return $this->planUsageService ??= app(PlanUsageService::class);
	}

	private function syncPlanUsagePeriodsIfAvailable(User $user, UserSubscription $subscription): void
	{
		try {
			if (! Schema::hasTable('plan_usage_periods')) {
				return;
			}

			$this->planUsageService()->ensureCurrentPeriodsForUser($user, $subscription);
		} catch (\Throwable $e) {
			Log::warning('StripeWebhookController.plan_usage_period_sync_failed', [
				'user_id' => $user->getKey(),
				'subscription_id' => $subscription->getKey(),
				'err' => $e->getMessage(),
			]);
		}
	}

    private function constructEventWithConfiguredSecrets(
        string $payload,
        ?string $sigHeader,
        array $secrets
    ): object {
        $lastSignatureException = null;

        foreach ($secrets as $secret) {
            try {
                return Webhook::constructEvent($payload, $sigHeader, $secret);
            } catch (SignatureVerificationException $e) {
                $lastSignatureException = $e;
            }
        }

        if ($lastSignatureException) {
            throw $lastSignatureException;
        }

        throw new \UnexpectedValueException('No Stripe webhook signing secrets configured.');
    }

	private function priceIdToPlanAndCycle(string $priceId): ?array
    {
        return app(StripePriceResolver::class)->planAndCycleFromPriceId($priceId);
    }

    private function resolveUserIdFromEvent($event, ?string $stripeCustomerId, ?string $stripeSubscriptionId): ?int
    {
        $object = $event->data->object ?? null;

        // Metadata user id (works for checkout session + subscription if you set it)
        $metaUserId = $object->metadata->billifty_user_id ?? null;
        if ($metaUserId) {
            return (int) $metaUserId;
        }

        // If we already have the subscription id, map to existing row
        if ($stripeSubscriptionId) {
            $id = DB::table('user_subscriptions')
                ->where('stripe_subscription_id', $stripeSubscriptionId)
                ->value('user_id');
            if ($id) {
                return (int) $id;
            }
        }

        // Fallback: user by stripe customer id
        if ($stripeCustomerId) {
            $id = DB::table('users')
                ->where('stripe_customer_id', $stripeCustomerId)
                ->value('id');
            if ($id) {
                return (int) $id;
            }
        }

        return null;
    }

    private function markInvoicePaid(object $session): void
    {
        $invoiceId = $session->metadata->invoice_id ?? null;

        if (! $invoiceId) {
            Log::warning('StripeWebhookController.invoice_payment_missing_invoice_id', [
                'session_id' => $session->id ?? null,
            ]);

            return;
        }

        $invoice = Invoices::query()->find($invoiceId);

        if (! $invoice) {
            Log::warning('StripeWebhookController.invoice_not_found', [
                'invoice_id' => $invoiceId,
                'session_id' => $session->id ?? null,
            ]);

            return;
        }

		$invoice->update([
			'status' => 'paid',
			'amount_due_cents' => 0,
			'paid_at' => now(),
			'pdf_path' => null,
			'pdf_status' => null,
			'pdf_generated_at' => null,
			'pdf_error' => null,
		]);

		$this->paymentReminderService->skipPendingRemindersBecausePaid($invoice->fresh());
	}

    private function markInvoicePaymentFailed(object $session): void
    {
        $invoiceId = $session->metadata->invoice_id ?? null;

        if (! $invoiceId) {
            Log::warning('StripeWebhookController.invoice_payment_failed_missing_invoice_id', [
                'session_id' => $session->id ?? null,
            ]);

            return;
        }

        $invoice = Invoices::query()->find($invoiceId);

        if (! $invoice) {
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

    private function processFailedInvoicePayment(object $event, object $session, int $invoiceId): void
    {
        Log::warning('StripeWebhookController.invoice_payment_failed', [
            'event_id' => $event->id ?? null,
            'event_type' => $event->type ?? null,
            'session_id' => $session->id ?? null,
            'invoice_id' => $invoiceId,
            'payment_status' => $session->payment_status ?? null,
            'status' => $session->status ?? null,
            'account' => $event->account ?? null,
        ]);

        $this->markInvoicePaymentFailed($session);
    }

    private function retrievePaymentData(object $event, object $session, Invoices $invoice): array
    {
        $stripePaymentInfo = $this->paymentInfoForMethod($invoice, 'stripe');
        $stripePaymentMethod = $stripePaymentInfo?->payment_method;
        $stripePaymentMethod = $stripePaymentMethod instanceof \BackedEnum
            ? $stripePaymentMethod->value
            : $stripePaymentMethod;
        $stripeAccountId = $event->account
            ?? $stripePaymentInfo?->stripe_account_id
            ?? null;
        $stripeRequestOptions = $stripeAccountId ? ['stripe_account' => $stripeAccountId] : [];
        $paymentIntentId = is_object($session->payment_intent ?? null)
            ? ($session->payment_intent->id ?? null)
            : ($session->payment_intent ?? null);

        $paymentIntent = $this->stripe->paymentIntents->retrieve(
            $paymentIntentId,
            [
                'expand' => [
                    'payment_method',
                    'latest_charge',
                ],
            ],
            $stripeRequestOptions
        );

        $paymentMethod = $paymentIntent->payment_method;
        $charge = $paymentIntent->latest_charge;

        $lineItems = $this->stripe->checkout->sessions->allLineItems(
            $session->id,
            ['limit' => 100],
            $stripeRequestOptions
        );

        $cardLast4 = null;
        $cardBrand = null;

        if ($paymentMethod && $paymentMethod->type === 'card') {
            $cardLast4 = $paymentMethod->card->last4;
            $cardBrand = $paymentMethod->card->brand;
        }

        $currencySymbol = Currency::whereCode($paymentIntent->currency)->value('symbol');

        $paymentData = [
            'invoice_number' => $invoice->invoice_number,
            'invoice_payment_method' => $stripePaymentMethod,
            'stripe_session_id' => $session->id,
            'stripe_payment_intent_id' => $paymentIntent->id,
            'payment_method' => $paymentMethod?->type,
            'card_brand' => $cardBrand,
            'card_last4' => $cardLast4,
            'amount_paid' => $paymentIntent->amount_received,
            'currency' => $paymentIntent->currency,
            'currency_symbol' => $currencySymbol,
            'payment_date' => now()->setTimestamp($paymentIntent->created),
            'receipt_url' => $charge?->receipt_url,
            'token' => $invoice->paymentLink?->token,
            'line_items' => collect($lineItems->data)->map(fn ($item) => [
                'description' => $item->description,
                'quantity' => $item->quantity,
                'amount_total' => $item->amount_total,
                'currency' => $item->currency,
            ])->values()->all(),
        ];

        return $paymentData;
    }

    private function paymentInfoForMethod(Invoices $invoice, string $method): ?object
    {
        return $invoice->businessProfile?->paymentInformations?->first(function ($paymentInfo) use ($method) {
            $paymentMethod = $paymentInfo?->payment_method;
            $paymentMethod = $paymentMethod instanceof \BackedEnum ? $paymentMethod->value : (string) $paymentMethod;

            return $paymentMethod === $method;
        });
    }

    private function processSuccessPayment(object $event, object $session, int $invoiceId): void
    {
        Log::info('StripeWebhookController.invoice_payment_processing', [
            'event_id' => $event->id ?? null,
            'event_type' => $event->type ?? null,
            'session_id' => $session->id ?? null,
            'invoice_id' => $invoiceId,
            'account' => $event->account ?? null,
        ]);

        $invoice = Invoices::query()->find($invoiceId);

        if (! $invoice) {
            Log::warning('StripeWebhookController.invoice_not_found_for_success_payment', [
                'event_id' => $event->id ?? null,
                'session_id' => $session->id ?? null,
                'invoice_id' => $invoiceId,
            ]);

            return;
        }

        $data = $this->retrievePaymentData($event, $session, $invoice);

        if ($event->type === 'checkout.session.async_payment_failed') {
            $this->markInvoicePaymentFailed($session);
        } else {
            $this->markInvoicePaid($session);
        }

        $paymentRecord = PaymentRecord::updateOrCreate(
            ['invoice_id' => $invoiceId],
            [
                'payment_method' => $data['invoice_payment_method'],
                'data' => $data,
                'token' => $data['token'],
            ]
        );

        Log::info('StripeWebhookController.payment_record_saved', [
            'event_id' => $event->id ?? null,
            'session_id' => $session->id ?? null,
            'invoice_id' => $invoiceId,
            'payment_record_id' => $paymentRecord->id,
            'created' => $paymentRecord->wasRecentlyCreated,
        ]);

        Mail::to($invoice->businessProfile?->email)
            ->send(new PaymentSuccessNotificationForBusinessProfileMail($invoice, $data));
        Mail::to($invoice->client?->email)
            ->send(new PaymentSuccessNotificationForClientMail($invoice, $data));
    }
}
