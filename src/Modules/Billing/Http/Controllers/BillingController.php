<?php

namespace BilliftySDK\SharedResources\Modules\Billing\Http\Controllers;

use BilliftySDK\SharedResources\Modules\Billing\Contracts\PaymentGateway;
use BilliftySDK\SharedResources\Modules\Billing\Services\Billing\ConfirmSubscriptionService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Stripe\StripeClient;

class BillingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

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
            'plan_code'     => ['required', Rule::in(['pro', 'premium'])],
            'billing_cycle' => ['required', Rule::in(['monthly', 'yearly'])],
        ]);

        $customerId = $gateway->ensureCustomer($user);
        $priceId    = $gateway->resolvePriceId($data['plan_code'], $data['billing_cycle']);

        $metadata = [
            'billifty_user_id' => (string) $user->id,
            'plan_code'        => $data['plan_code'],
            'billing_cycle'    => $data['billing_cycle'],
        ];

        // ✅ Idempotency + recovery is handled inside StripePaymentGateway
        $subscription = $gateway->createIncompleteSubscription($customerId, $priceId, $metadata);

        $latestInvoice = $subscription->latest_invoice ?? null;
        $paymentIntent = null;

        if (is_object($latestInvoice)) {
            $paymentIntent = $latestInvoice->payment_intent ?? null;

            if (is_string($paymentIntent)) {
                $paymentIntent = $stripe->paymentIntents->retrieve($paymentIntent);
            }
        }

        // If Stripe still did not attach a PI, create one manually from invoice
        if (! $paymentIntent) {
            Log::warning('createSubscriptionIntent: no payment_intent on subscription invoice (will create manual PI)', [
                'subscription_id' => $subscription->id ?? null,
                'invoice_id'      => is_object($latestInvoice) ? ($latestInvoice->id ?? null) : (string) $latestInvoice,
            ]);

            if (!is_object($latestInvoice) || !isset($latestInvoice->amount_due, $latestInvoice->currency)) {
                return response()->json([
                    'message' => 'Unable to prepare payment (invoice missing amount_due/currency).',
                ], 500);
            }

            $paymentIntent = $stripe->paymentIntents->create([
                'amount'   => $latestInvoice->amount_due,
                'currency' => $latestInvoice->currency,
                'customer' => $customerId,
                'automatic_payment_methods' => ['enabled' => true],
                'metadata' => [
                    'billifty_user_id' => (string) $user->id,
                    'subscription_id'  => $subscription->id,
                    'plan_code'        => $data['plan_code'],
                    'billing_cycle'    => $data['billing_cycle'],
                ],
            ]);
        }

        return response()->json([
            'subscription_id'   => $subscription->id,
            'status'            => $subscription->status,
            'client_secret'     => $paymentIntent->client_secret,
            'payment_intent_id' => $paymentIntent->id,
        ]);
    }

    public function confirmSubscription(Request $request, ConfirmSubscriptionService $service)
    {
        $user = $request->user();

        $data = $request->validate([
            'subscription_id'   => ['required'],
            'plan_code'         => ['required', Rule::in(['pro', 'premium'])],
            'billing_cycle'     => ['required', Rule::in(['monthly', 'yearly'])],
            'payment_intent_id' => ['nullable'],
        ]);

        return response()->json(
            $service->handle(
                $user,
                $data['subscription_id'],
                $data['plan_code'],
                $data['billing_cycle'],
                $data['payment_intent_id'] ?? null
            )
        );
    }
}
