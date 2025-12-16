<?php

namespace BilliftySDK\SharedResources\Modules\Billing\Http\Controllers;

use BilliftySDK\SharedResources\Modules\Billing\Contracts\PaymentGateway;
use BilliftySDK\SharedResources\Modules\Billing\Models\UserSubscription;
use BilliftySDK\SharedResources\Modules\Billing\Services\Billing\ConfirmSubscriptionService;
use BilliftySDK\SharedResources\Modules\User\Models\Plan;
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

        // Idempotency + recovery is handled inside StripePaymentGateway
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

	/**
     * Change plan for an EXISTING paid subscription (upgrade/downgrade).
     */
    public function changePlan(
        Request $request,
        PaymentGateway $gateway,
        StripeClient $stripe
    ) {
        $user = $request->user();

        $data = $request->validate([
            'plan_code'     => ['required', Rule::in(['pro', 'premium'])],
            'billing_cycle' => ['required', Rule::in(['monthly', 'yearly'])],
            'proration_behavior' => ['nullable', Rule::in(['create_prorations', 'none'])],
        ]);

        /** @var UserSubscription|null $currentSub */
        $currentSub = UserSubscription::query()
            ->where('user_id', $user->id)
            ->whereIn('status', ['active', 'trialing', 'past_due', 'incomplete'])
            ->orderByDesc('id')
            ->first();

        if (! $currentSub || ! $currentSub->stripe_subscription_id) {
            return response()->json([
                'message' => 'No active subscription to change.',
            ], 422);
        }

        $priceId = $gateway->resolvePriceId($data['plan_code'], $data['billing_cycle']);

        $prorationBehavior = $data['proration_behavior'] ?? 'create_prorations';

        $subscription = $gateway->changeSubscriptionPrice(
            $currentSub->stripe_subscription_id,
            $priceId,
            $prorationBehavior
        );

        // Update local DB
        $planCodeNormalized = strtolower($data['plan_code']);
        $planId = Plan::where('code', $planCodeNormalized)->value('id');

        if ($planId) {
            $stripeItem  = $subscription->items->data[0] ?? null;
            $stripePrice = $stripeItem?->price ?? null;

            $currentSub->update([
                'plan_id'       => $planId,
                'plan_code'     => $planCodeNormalized,
                'billing_cycle' => $data['billing_cycle'],
                'currency'      => $stripePrice->currency ?? 'usd',
                'unit_amount'   => $stripePrice->unit_amount ?? 0,
                'status'        => $subscription->status,
                'raw_payload'   => $subscription->toArray(),
            ]);
        }

        return response()->json([
            'message'       => 'Subscription updated.',
            'subscription'  => $currentSub->fresh(),
        ]);
    }

    /**
     * Cancel subscription (usually at period end).
     */
    public function cancelSubscription(
        Request $request,
        PaymentGateway $gateway
    ) {
        $user = $request->user();

        $data = $request->validate([
            'at_period_end' => ['nullable', 'boolean'],
        ]);

        /** @var UserSubscription|null $currentSub */
        $currentSub = UserSubscription::query()
            ->where('user_id', $user->id)
            ->whereIn('status', ['active', 'trialing', 'past_due', 'incomplete'])
            ->orderByDesc('id')
            ->first();

        if (! $currentSub || ! $currentSub->stripe_subscription_id) {
            return response()->json([
                'message' => 'No active subscription to cancel.',
            ], 422);
        }

        $atPeriodEnd = $data['at_period_end'] ?? true;

        $subscription = $gateway->cancelSubscription(
            $currentSub->stripe_subscription_id,
            $atPeriodEnd
        );

        $currentSub->update([
            'status'      => $subscription->status,
            'raw_payload' => $subscription->toArray(),
        ]);

        return response()->json([
            'message'      => $atPeriodEnd
                ? 'Subscription will cancel at period end.'
                : 'Subscription canceled immediately.',
            'subscription' => $currentSub->fresh(),
        ]);
    }

    /**
     * Create a SetupIntent for updating payment method with Stripe Payment Element.
     */
    public function createPaymentMethodSetupIntent(
        Request $request,
        PaymentGateway $gateway
    ) {
        $user = $request->user();

        $customerId = $gateway->ensureCustomer($user);

        $setupIntent = $gateway->createCustomerSetupIntent($customerId, [
            'billifty_user_id' => (string) $user->id,
        ]);

        return response()->json([
            'client_secret' => $setupIntent->client_secret,
        ]);
    }

    /**
     * After Payment Element (setup mode) succeeds, update default payment method.
     */
    public function updatePaymentMethod(
        Request $request,
        PaymentGateway $gateway
    ) {
        $user = $request->user();

        $data = $request->validate([
            'payment_method_id' => ['required', 'string'],
        ]);

        /** @var UserSubscription|null $currentSub */
        $currentSub = UserSubscription::query()
            ->where('user_id', $user->id)
            ->whereIn('status', ['active', 'trialing', 'past_due', 'incomplete'])
            ->orderByDesc('id')
            ->first();

        $gateway->updateDefaultPaymentMethodForCustomerAndSubscription(
            $user->stripe_customer_id,
            $currentSub?->stripe_subscription_id,
            $data['payment_method_id']
        );

        return response()->json([
            'message' => 'Payment method updated.',
        ]);
    }

	/**
	 * Stripe Billing Portal session (low maintenance manage cards).
	 */
	public function createPortalSession(Request $request, PaymentGateway $gateway)
	{
		$user = $request->user();

		// Make sure Stripe customer exists
		$customerId = $gateway->ensureCustomer($user);

		// Where Stripe should send them back after managing billing
		$data = $request->validate([
			'return_url' => ['nullable', 'string'],
		]);

		// fallback return url (frontend should pass a full URL ideally)
		$defaultReturnUrl = config('app.url') . '/app/account/manage-subscription';

		$returnUrl = $data['return_url'] ?? $defaultReturnUrl;

		$url = $gateway->createBillingPortalSession($customerId, $returnUrl);

		return response()->json([
			'url' => $url,
		]);
	}

}
