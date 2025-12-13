<?php

namespace BilliftySDK\SharedResources\Modules\Billing\Http\Controllers;

use BilliftySDK\SharedResources\Modules\Billing\Models\UserSubscription;
use BilliftySDK\SharedResources\Modules\Billing\Services\Billing\ConfirmSubscriptionService;
use BilliftySDK\SharedResources\Modules\Billing\Services\Billing\StripePaymentGateway;
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

    public function confirmSubscription(Request $request, ConfirmSubscriptionService $service)
	{
		$user = $request->user();

		$data = $request->validate([
			'subscription_id'   => ['required', 'string'],
			'plan_code'         => ['required', 'string', Rule::in(['pro', 'premium'])],
			'billing_cycle'     => ['required', 'string', Rule::in(['monthly', 'yearly'])],
			'payment_intent_id' => ['nullable', 'string'],
		]);

		// Controller does not save anything now:
		$result = $service->handle(
			$user,
			$data['subscription_id'],
			$data['plan_code'],
			$data['billing_cycle'],
			$data['payment_intent_id'] ?? null
		);

		return response()->json($result);
	}
}
