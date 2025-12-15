<?php

namespace BilliftySDK\SharedResources\Modules\Billing\Http\Controllers;

use App\Http\Controllers\Controller;
use BilliftySDK\SharedResources\Modules\Billing\Models\StripeWebhookEvents;
use BilliftySDK\SharedResources\Modules\Billing\Models\UserSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Stripe\Exception\SignatureVerificationException;
use Stripe\StripeClient;
use Stripe\Webhook;

class StripeWebhookController extends Controller
{
    public function __construct(private StripeClient $stripe) {}

    public function handle(Request $request)
    {
        $payload   = $request->getContent(); // raw body (string)
        $sigHeader = $request->header('Stripe-Signature');
        $secret    = config('services.stripe.webhook_secret');

        Log::info('StripeWebhookController.hit', [
            'path'    => $request->path(),
            'ip'      => $request->ip(),
            'has_sig' => (bool) $sigHeader,
            'len'     => strlen($payload),
        ]);

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $secret);
        } catch (\UnexpectedValueException $e) {
            Log::warning('StripeWebhookController.invalid_payload', ['err' => $e->getMessage()]);
            return response('Invalid payload', 400);
        } catch (SignatureVerificationException $e) {
            Log::warning('StripeWebhookController.invalid_signature', ['err' => $e->getMessage()]);
            return response('Invalid signature', 400);
        }

        // Store payload string (json) in stripe_webhook_events
        $payloadArray = json_decode($payload, true);
        $payloadJson  = is_array($payloadArray)
            ? json_encode($payloadArray, JSON_UNESCAPED_SLASHES)
            : json_encode(['raw' => $payload], JSON_UNESCAPED_SLASHES);

        $object = $event->data->object ?? null;

        $stripeCustomerId     = $object->customer ?? ($object->customer_id ?? null);
        $stripeSubscriptionId = $object->subscription ?? null;

        $userId = $this->resolveUserIdFromEvent($event, $stripeCustomerId, $stripeSubscriptionId);

        StripeWebhookEvents::updateOrCreate(
            ['event_id' => $event->id],
            [
                'user_id'                => $userId,
                'type'                   => $event->type,
                'api_version'            => $event->api_version ?? null,
                'livemode'               => (bool) ($event->livemode ?? false),
                'stripe_customer_id'     => $stripeCustomerId,
                'stripe_subscription_id' => $stripeSubscriptionId,
                'payload'                => $payloadJson, // string
                'received_at'            => now(),
            ]
        );

        // Example handler: invoice paid -> update subscription raw payload
        if ($event->type === 'invoice.payment_succeeded') {
			$invoice = $event->data->object;

			$subId = $invoice->subscription ?? null;

			if ($subId) {
				try {
					$sub = $this->stripe->subscriptions->retrieve($subId, []);

					UserSubscription::query()
						->where('stripe_subscription_id', $subId)
						->orWhere('stripe_customer_id', $stripeCustomerId)
						->update([
							'status'      => $sub->status,
							'starts_at'   => \Carbon\Carbon::createFromTimestamp($sub->current_period_start),
							'renews_at'   => \Carbon\Carbon::createFromTimestamp($sub->current_period_end),
							'raw_payload' => is_array($payloadArray) ? $payloadArray : ['raw' => $payload],
							'updated_at'  => now(),
						]);
				} catch (\Throwable $e) {
					Log::error('StripeWebhookController.subscription_retrieve_failed', [
						'subscription_id' => $subId,
						'err' => $e->getMessage(),
					]);
				}
			} else {
				Log::warning('StripeWebhookController.invoice_payment_succeeded_missing_subscription', [
					'invoice_id' => $invoice->id ?? null,
				]);
			}
		}


        return response('OK', 200);
    }

    private function resolveUserIdFromEvent($event, ?string $stripeCustomerId, ?string $stripeSubscriptionId): ?int
    {
        $object = $event->data->object ?? null;

        // 1) Best: metadata on object itself
        $metaUserId = $object->metadata->billifty_user_id ?? null;
        if ($metaUserId) {
            return (int) $metaUserId;
        }

        // 2) subscription lookup
        if ($stripeSubscriptionId) {
            $id = DB::table('user_subscriptions')
                ->where('stripe_subscription_id', $stripeSubscriptionId)
                ->value('user_id');

            if ($id) return (int) $id;
        }

        // 3) customer lookup (users table)
        if ($stripeCustomerId) {
            $id = DB::table('users')
                ->where('stripe_customer_id', $stripeCustomerId)
                ->value('id');

            if ($id) return (int) $id;
        }

        // 4) fallback: retrieve customer metadata from Stripe
        if ($stripeCustomerId) {
            try {
                $customer   = $this->stripe->customers->retrieve($stripeCustomerId);
                $metaUserId = $customer->metadata->billifty_user_id ?? null;

                if ($metaUserId) return (int) $metaUserId;
            } catch (\Throwable $e) {
                Log::warning('StripeWebhookController.customer_lookup_failed', [
                    'customer' => $stripeCustomerId,
                    'err'      => $e->getMessage(),
                ]);
            }
        }

        return null;
    }
}
