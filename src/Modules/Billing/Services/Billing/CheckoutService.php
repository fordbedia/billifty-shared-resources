<?php

namespace BilliftySDK\SharedResources\Modules\Billing\Services\Billing;

use BilliftySDK\SharedResources\Modules\Billing\Contracts\PaymentGateway;
use BilliftySDK\SharedResources\Modules\Billing\Contracts\SubscriptionResult;
use BilliftySDK\SharedResources\Modules\Billing\DTO\SubscriptionRequest;

final class CheckoutService
{
    public function __construct(
        private PaymentGateway $gateway,
        private PlanPriceResolver $prices
    ) {}

    public function listSavedCards(string $customerId): array
    {
        return $this->gateway->listCardPaymentMethods($customerId);
    }

    public function removeSavedCard(string $paymentMethodId): void
    {
        $this->gateway->detachPaymentMethod($paymentMethodId);
    }

    public function subscribe(SubscriptionRequest $dto): SubscriptionResult
    {
        $customerId = $this->gateway->ensureCustomer($dto->user);
        $priceId    = $this->prices->resolve($dto->planCode, $dto->billingCycle);

        if ($dto->saveCard) {
            $this->gateway->attachPaymentMethod($dto->paymentMethodId, $customerId, makeDefault: true);
        }

        $result = $this->gateway->createSubscription($customerId, $priceId, $dto->paymentMethodId);

        // TODO: persist subscription mapping in your DB (user->plan_id, external sub id, etc.)

        return $result;
    }
}