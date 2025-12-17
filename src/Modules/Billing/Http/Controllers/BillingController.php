<?php

namespace BilliftySDK\SharedResources\Modules\Billing\Http\Controllers;

use BilliftySDK\SharedResources\Modules\Billing\Contracts\PaymentGateway;
use BilliftySDK\SharedResources\Modules\Billing\Models\UserSubscription;
use BilliftySDK\SharedResources\Modules\User\Models\Plan;
use Carbon\Carbon;
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

    /**
     * Create a Stripe Checkout session (subscription).
     * Frontend will redirect user to returned `url`.
     */
    public function createCheckoutSession(Request $request, PaymentGateway $gateway)
    {
        $user = $request->user();

        $data = $request->validate([
            'plan_code'     => ['required', Rule::in(['pro', 'premium'])],
            'billing_cycle' => ['required', Rule::in(['monthly', 'yearly'])],
            'success_url'   => ['required', 'string'],
            'cancel_url'    => ['required', 'string'],
        ]);

        $customerId = $gateway->ensureCustomer($user);
        $priceId    = $gateway->resolvePriceId($data['plan_code'], $data['billing_cycle']);

        Log::info('BillingController.createCheckoutSession.start', [
            'user_id'       => $user->id,
            'plan_code'     => $data['plan_code'],
            'billing_cycle' => $data['billing_cycle'],
            'customer_id'   => $customerId,
            'price_id'      => $priceId,
        ]);

        $metadata = [
            'billifty_user_id' => (string) $user->id,
            'plan_code'        => $data['plan_code'],
            'billing_cycle'    => $data['billing_cycle'],
        ];

        $url = $gateway->createCheckoutSessionUrl(
            $customerId,
            $priceId,
            $data['success_url'],
            $data['cancel_url'],
            $metadata
        );

        return response()->json(['url' => $url]);
    }

    /**
     * Stripe Billing Portal (manage: upgrade/downgrade/cancel/payment method).
     */
    public function createPortalSession(Request $request, PaymentGateway $gateway)
    {
        $user = $request->user();

        $customerId = $gateway->ensureCustomer($user);

        $data = $request->validate([
            'return_url' => ['nullable', 'string'],
        ]);

        $defaultReturnUrl = config('services.stripe.return_url');
        $returnUrl = $data['return_url'] ?? $defaultReturnUrl;

        $url = $gateway->createBillingPortalSession($customerId, $returnUrl);

        return response()->json(['url' => $url]);
    }

	public function cancelMySubscription(Request $request, PaymentGateway $gateway, StripeClient $stripe)
	{
		$user = $request->user();

		/** @var UserSubscription|null $currentSub */
		$currentSub = UserSubscription::query()
			->where('user_id', $user->id)
			->whereIn('status', ['active', 'trialing', 'past_due', 'incomplete'])
			->orderByDesc('id')
			->first();

		if (! $currentSub || ! $currentSub->stripe_subscription_id) {
			// already free / nothing to cancel
			$freeId = Plan::where('code', 'free')->value('id');
			if ($freeId) {
				$user->forceFill(['plan_id' => $freeId])->save();
			}

			return response()->json(['message' => 'No subscription to cancel.']);
		}

		// ✅ Cancel immediately (not at period end)
		$sub = $stripe->subscriptions->cancel($currentSub->stripe_subscription_id, []);

		// Set user to Free immediately (webhook will also sync)
		$freeId = Plan::where('code', 'free')->value('id');
		if ($freeId) {
			$user->forceFill(['plan_id' => $freeId])->save();
		}

		$currentSub->update([
			'status'      => $sub->status ?? 'canceled',
			'raw_payload' => $sub->toArray(),
		]);

		return response()->json([
			'message' => 'Subscription canceled.',
		]);
	}

	public function confirmCheckout(Request $request, StripeClient $stripe)
	{
		$user = $request->user();

		$data = $request->validate([
			'session_id' => ['required', 'string'],
		]);

		// Retrieve session + subscription
		$session = $stripe->checkout->sessions->retrieve($data['session_id'], [
			'expand' => ['subscription', 'subscription.items.data.price'],
		]);

		// Safety: ensure this session belongs to this customer
		if (($session->customer ?? null) !== $user->stripe_customer_id) {
			return response()->json(['message' => 'Session does not belong to this user.'], 403);
		}

		$subscription = $session->subscription ?? null;
		if (! $subscription || empty($subscription->id)) {
			return response()->json(['message' => 'No subscription found on checkout session.'], 422);
		}

		// Determine plan + cycle from metadata (fallback to priceId map)
		$sessionMeta = (array) ($session->metadata ?? []);
		$subMeta     = (array) ($subscription->metadata ?? []);

		$planCode = strtolower((string) ($subMeta['plan_code'] ?? $sessionMeta['plan_code'] ?? ''));
		$billingCycle = strtolower((string) ($subMeta['billing_cycle'] ?? $sessionMeta['billing_cycle'] ?? ''));

		$stripeItem  = $subscription->items->data[0] ?? null;
		$stripePrice = $stripeItem?->price ?? null;
		$priceId     = $stripePrice->id ?? null;

		if ((!$planCode || !$billingCycle) && $priceId) {
			$map = $this->priceIdToPlanAndCycle($priceId);
			if ($map) {
				$planCode     = $planCode ?: $map['plan_code'];
				$billingCycle = $billingCycle ?: $map['billing_cycle'];
			}
		}

		$planId = $planCode ? Plan::where('code', $planCode)->value('id') : null;
		$freeId = Plan::where('code', 'free')->value('id');

		$startsAt = isset($subscription->current_period_start)
			? Carbon::createFromTimestamp((int) $subscription->current_period_start)
			: null;

		$renewsAt = isset($subscription->current_period_end)
			? Carbon::createFromTimestamp((int) $subscription->current_period_end)
			: null;

		// Upsert user_subscriptions
		UserSubscription::updateOrCreate(
			['stripe_subscription_id' => $subscription->id],
			[
				'user_id'            => (int) $user->id,
				'plan_id'            => $planId ?? $freeId,
				'plan_code'          => $planCode ?: 'free',
				'billing_cycle'      => $billingCycle ?: 'monthly',
				'stripe_customer_id' => (string) ($session->customer ?? $user->stripe_customer_id),
				'currency'           => $stripePrice->currency ?? 'usd',
				'unit_amount'        => $stripePrice->unit_amount ?? 0,
				'status'             => $subscription->status ?? 'incomplete',
				'starts_at'          => $startsAt,
				'renews_at'          => $renewsAt,
				'raw_payload'        => method_exists($subscription, 'toArray') ? $subscription->toArray() : [],
			]
		);

		// Only set plan_id when active/trialing
		$isPaid = in_array((string) ($subscription->status ?? ''), ['active', 'trialing'], true);

		$user->forceFill([
			'plan_id' => ($isPaid && $planId) ? $planId : $freeId,
		])->save();

		return response()->json([
			'message' => 'Subscription confirmed.',
			'status'  => $subscription->status ?? null,
			'plan_code' => $planCode,
			'billing_cycle' => $billingCycle,
		]);
	}

	// same helper as your webhook controller (you can DRY into a service later)
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

}
