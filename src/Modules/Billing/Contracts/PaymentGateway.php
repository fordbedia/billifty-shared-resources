<?php

namespace BilliftySDK\SharedResources\Modules\Billing\Contracts;

use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

interface PaymentGateway
{
    public function ensureCustomer(AuthenticatableContract $user): string;

    public function resolvePriceId(string $planCode, string $billingCycle): string;

    public function createCheckoutSessionUrl(
        string $customerId,
        string $priceId,
        string $successUrl,
        string $cancelUrl,
        array $metadata = []
    ): string;

    public function createBillingPortalSession(string $customerId, string $returnUrl): string;

	public function markUserAsFree(?int $userId, ?string $stripeCustomerId, ?string $stripeSubscriptionId, ?string $payloadJson = null): void;
}
