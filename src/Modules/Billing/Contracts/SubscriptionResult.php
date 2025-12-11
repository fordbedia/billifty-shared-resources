<?php

namespace BilliftySDK\SharedResources\Modules\Billing\Contracts;

final class SubscriptionResult
{
    public function __construct(
        public readonly bool $requiresAction,
        public readonly ?string $clientSecret,
        public readonly ?string $subscriptionId
    ) {}
}