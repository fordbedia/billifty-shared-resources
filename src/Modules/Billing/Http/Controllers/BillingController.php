<?php

namespace BilliftySDK\SharedResources\Modules\Billing\Http\Controllers;

use Illuminate\Routing\Controller;
use BilliftySDK\SharedResources\Modules\Billing\Contracts\PaymentGateway;
use BilliftySDK\SharedResources\Modules\Billing\Services\Billing\CheckoutService;
use BilliftySDK\SharedResources\Modules\Billing\Services\Billing\StripeCustomerService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Stripe\StripeClient;

class BillingController extends Controller
{
    public function __construct()
    {
        // Passport API guard
        $this->middleware('auth:api');
    }

    /**
     * Create a Subscription + PaymentIntent for Stripe Payment Element.
     * - Always saves the payment method on the Stripe Customer
     *   so Payment Element can reuse it later.
     */
    public function createSubscriptionIntent(
        Request $request,
        PaymentGateway $gateway,
        StripeClient $stripe
    ) {
        $user = $request->user();

        $data = $request->validate([
            'plan_code'     => ['required', 'string', 'in:pro,premium'],
            'billing_cycle' => ['required', 'string', 'in:monthly,yearly'],
        ]);

        // 1) Ensure Stripe customer & get price ID
        $customerId = $gateway->ensureCustomer($user);
        $priceId    = $gateway->resolvePriceId($data['plan_code'], $data['billing_cycle']);

        // 2) Create default_incomplete subscription (saves PM on subscription)
        $subscription = $gateway->createIncompleteSubscription($customerId, $priceId);

        // 3) Re-fetch subscription with expanded latest_invoice.payment_intent
        $subscription = $stripe->subscriptions->retrieve($subscription->id, [
            'expand' => ['latest_invoice.payment_intent'],
        ]);

        $latestInvoice = $subscription->latest_invoice ?? null;
        $paymentIntent = $latestInvoice?->payment_intent ?? null;

        // 4) If Stripe didn’t attach a payment_intent, create one ourselves
        if (! $paymentIntent) {
            if (! $latestInvoice || ! isset($latestInvoice->amount_due, $latestInvoice->currency)) {
                \Log::error('Stripe subscription has no usable invoice', [
                    'subscription_id' => $subscription->id ?? null,
                    'latest_invoice'  => $latestInvoice,
                ]);

                return response()->json([
                    'message' => 'Unable to prepare payment for this subscription (no invoice).',
                ], 500);
            }

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
}