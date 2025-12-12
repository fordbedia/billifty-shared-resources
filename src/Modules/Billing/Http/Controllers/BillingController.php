<?php

namespace BilliftySDK\SharedResources\Modules\Billing\Http\Controllers;

use BilliftySDK\SharedResources\Modules\Billing\Models\UserSubscription;
use BilliftySDK\SharedResources\Modules\User\Models\Plan;
use Illuminate\Routing\Controller;
use BilliftySDK\SharedResources\Modules\Billing\Contracts\PaymentGateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Stripe\StripeClient;
use Illuminate\Support\Facades\Log;

class BillingController extends Controller
{
    public function __construct()
    {
        // Passport API guard
        $this->middleware('auth:api');
    }

    /**
     * Create a Subscription + PaymentIntent for Stripe Payment Element.
     * - Always ensures a PaymentIntent is available:
     *   - use subscription.latest_invoice.payment_intent if Stripe created it
     *   - otherwise create a PaymentIntent manually from the invoice
     *
     * NOTE: This endpoint *only* prepares the subscription & payment.
     * No DB writes to users.plan_id or user_subscriptions happen here.
     * Those are done in confirmSubscription() after payment succeeds.
     */
    public function createSubscriptionIntent(
        Request $request,
        PaymentGateway $gateway,
        StripeClient $stripe
    ) {
        $user = $request->user();

        Log::info('BillingController.createSubscriptionIntent.start', [
            'user_id'       => $user->id,
            'plan_code'     => $request->input('plan_code'),
            'billing_cycle' => $request->input('billing_cycle'),
        ]);

        $data = $request->validate([
            'plan_code'     => ['required', 'string', 'in:pro,premium'],
            'billing_cycle' => ['required', 'string', 'in:monthly,yearly'],
        ]);

        // 1) Ensure Stripe customer & get price ID
        $customerId = $gateway->ensureCustomer($user);
        $priceId    = $gateway->resolvePriceId($data['plan_code'], $data['billing_cycle']);

        // 2) Create default_incomplete subscription and expand latest_invoice.payment_intent
        $subscription = $gateway->createIncompleteSubscription($customerId, $priceId);

        $latestInvoice = $subscription->latest_invoice ?? null;
        $paymentIntent = $latestInvoice?->payment_intent ?? null;

        // 3) If Stripe did NOT create a payment_intent, create one ourselves
        if (! $paymentIntent) {
            Log::error('createSubscriptionIntent: no payment_intent on subscription invoice', [
                'subscription_id' => $subscription->id ?? null,
                'latest_invoice'  => $latestInvoice,
            ]);

            if (! $latestInvoice || ! isset($latestInvoice->amount_due, $latestInvoice->currency)) {
                Log::error('createSubscriptionIntent: invoice missing amount_due or currency', [
                    'subscription_id' => $subscription->id ?? null,
                    'latest_invoice'  => $latestInvoice,
                ]);

                return response()->json([
                    'message' => 'Unable to prepare payment for this subscription (no invoice amount).',
                ], 500);
            }

            // Manually create a PaymentIntent for this invoice amount
            $paymentIntent = $stripe->paymentIntents->create([
                'amount'   => $latestInvoice->amount_due,
                'currency' => $latestInvoice->currency,
                'customer' => $customerId,
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
                'metadata' => [
                    'subscription_id' => $subscription->id,
                    'invoice_id'      => $latestInvoice->id,
                    'plan_code'       => $data['plan_code'],
                    'billing_cycle'   => $data['billing_cycle'],
                ],
            ]);
        }

        return response()->json([
            'subscription_id' => $subscription->id,
            'status'          => $subscription->status,
            'client_secret'   => $paymentIntent->client_secret,
        ]);
    }

    /**
     * Create a Stripe Billing Portal Session so the user can:
     * - View saved payment methods
     * - Remove cards
     * - Change default card
     * - Update billing info
     */
    public function createPortalSession(Request $request, StripeClient $stripe)
    {
        $user = $request->user();

        if (! $user->stripe_customer_id) {
            return response()->json([
                'message' => 'No Stripe customer found for this user.',
            ], 404);
        }

        $returnUrl = config('app.url') . '/app/billing'; // adjust to your billing page

        $session = $stripe->billingPortal->sessions->create([
            'customer'   => $user->stripe_customer_id,
            'return_url' => $returnUrl,
        ]);

        return response()->json([
            'url' => $session->url,
        ]);
    }

    public function confirmSubscription(Request $request, StripeClient $stripe)
    {
        $user = $request->user();

        $data = $request->validate([
            'subscription_id'   => ['required', 'string'],
            'plan_code'         => ['required', 'string', Rule::in(['pro', 'premium'])],
            'billing_cycle'     => ['required', 'string', Rule::in(['monthly', 'yearly'])],
            // 👇 NEW: allow payment_intent_id from frontend as a fallback
            'payment_intent_id' => ['nullable', 'string'],
        ]);

        return DB::transaction(function () use ($data, $user, $stripe) {
            Log::info('BillingController.confirmSubscription.start', [
                'user_id'           => $user->id,
                'subscription_id'   => $data['subscription_id'],
                'plan_code'         => $data['plan_code'],
                'billing_cycle'     => $data['billing_cycle'],
                'payment_intent_id' => $data['payment_intent_id'] ?? null,
            ]);

            // 1) Load subscription, including the invoice + payment_intent if available
            $subscription = $stripe->subscriptions->retrieve($data['subscription_id'], [
                'expand' => [
                    'items.data.price',
                    'latest_invoice.payment_intent',
                ],
            ]);

            $latestInvoice = $subscription->latest_invoice ?? null;
            $paymentIntent = $latestInvoice?->payment_intent ?? null;

            // 👇 NEW: if Stripe did not attach a payment_intent to the invoice,
            // but frontend told us which PaymentIntent was confirmed,
            // we retrieve it directly from Stripe.
            if (! $paymentIntent && ! empty($data['payment_intent_id'])) {
                try {
                    $paymentIntent = $stripe->paymentIntents->retrieve($data['payment_intent_id']);
                } catch (\Throwable $e) {
                    Log::error('confirmSubscription: failed to retrieve payment_intent by id', [
                        'payment_intent_id' => $data['payment_intent_id'],
                        'exception'         => $e->getMessage(),
                    ]);
                }
            }

            // 2) Guard: make sure payment actually succeeded
            if (! $paymentIntent || $paymentIntent->status !== 'succeeded') {
                Log::warning('confirmSubscription: payment not succeeded', [
                    'subscription_id'       => $subscription->id ?? null,
                    'subscription_status'   => $subscription->status ?? null,
                    'invoice_id'            => $latestInvoice->id ?? null,
                    'payment_intent_id'     => $paymentIntent->id ?? null,
                    'payment_intent_status' => $paymentIntent->status ?? null,
                ]);

                return response()->json([
                    'message'               => 'Payment for this subscription is not completed.',
                    'subscription_status'   => $subscription->status ?? null,
                    'payment_intent_status' => $paymentIntent->status ?? null,
                ], 422);
            }

            // (Optional) log status
            Log::info('confirmSubscription: payment succeeded', [
                'subscription_id'     => $subscription->id,
                'subscription_status' => $subscription->status,
                'payment_intent_id'   => $paymentIntent->id,
            ]);

            // 3) Resolve plan_id from plan_code
            $planCode = strtolower($data['plan_code']);
            $planId   = Plan::where('code', $planCode)->value('id');

            if (! $planId) {
                Log::error('confirmSubscription: plan not found', [
                    'plan_code' => $planCode,
                ]);

                return response()->json([
                    'message' => 'Plan not found for this subscription.',
                ], 500);
            }

            // 4) Update users.plan_id
            $user->forceFill([
                'plan_id' => $planId,
            ])->save();

            Log::info('confirmSubscription: user.plan_id updated', [
                'user_id' => $user->id,
                'plan_id' => $planId,
            ]);

            // 5) Persist user_subscriptions row
            $stripeItem  = $subscription->items->data[0] ?? null;
            $stripePrice = $stripeItem?->price ?? null;

            $subscriptionModel = UserSubscription::updateOrCreate(
                [
                    'user_id'                => $user->id,
                    'stripe_subscription_id' => $subscription->id,
                ],
                [
                    'plan_id'            => $planId,
                    'plan_code'          => $planCode,
                    'billing_cycle'      => $data['billing_cycle'],
                    'stripe_customer_id' => $subscription->customer,
                    'currency'           => $stripePrice->currency ?? 'usd',
                    'unit_amount'        => $stripePrice->unit_amount ?? 0,
                    'status'             => $subscription->status,
                    'starts_at'          => isset($subscription->current_period_start)
                        ? now()->createFromTimestamp($subscription->current_period_start)
                        : null,
                    'renews_at'          => isset($subscription->current_period_end)
                        ? now()->createFromTimestamp($subscription->current_period_end)
                        : null,
                    'raw_payload'        => $subscription->toArray(),
                ]
            );

            Log::info('confirmSubscription: subscription persisted', [
                'user_subscription_id' => $subscriptionModel->id,
            ]);

            return response()->json([
                'status'        => 'ok',
                'plan_code'     => $planCode,
                'billing_cycle' => $data['billing_cycle'],
            ]);
        });
    }
}
