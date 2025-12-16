<?php

namespace BilliftySDK\SharedResources\Modules\Billing\Contracts;

use BilliftySDK\SharedResources\Modules\User\Models\User;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Stripe\SetupIntent;
use Stripe\Subscription;

interface PaymentGateway
{
    public function ensureCustomer(AuthenticatableContract $user): string;

    public function resolvePriceId(string $planCode, string $billingCycle): string;

    public function createIncompleteSubscription(
		string $customerId,
		string $priceId,
		array $metadata
	): Subscription;

	/**
     * For managing an existing subscription (upgrade/downgrade).
     */
    public function changeSubscriptionPrice(
        string $subscriptionId,
        string $priceId,
        string $prorationBehavior = 'create_prorations'
    ): Subscription;

    /**
     * For cancelling an existing subscription.
     */
    public function cancelSubscription(
        string $subscriptionId,
        bool $atPeriodEnd = true
    ): Subscription;

    /**
     * For updating payment method with Stripe Payment Element (setup mode).
     */
    public function createCustomerSetupIntent(
        string $customerId,
        array $metadata = []
    ): SetupIntent;

    /**
     * Attach + set default payment method for customer and subscription.
     */
    public function updateDefaultPaymentMethodForCustomerAndSubscription(
        string $customerId,
        ?string $subscriptionId,
        string $paymentMethodId
    ): void;

	public function createBillingPortalSession(string $customerId, string $returnUrl): string;
}