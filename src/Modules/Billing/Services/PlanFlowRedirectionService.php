<?php

namespace BilliftySDK\SharedResources\Modules\Billing\Services;

use BilliftySDK\SharedResources\Modules\Billing\Contracts\PaymentGateway;
use BilliftySDK\SharedResources\Modules\Billing\Domain\FlowRedirectionEnum;
use BilliftySDK\SharedResources\Modules\Billing\Models\UserSubscription;
use BilliftySDK\SharedResources\Modules\Invoicing\Enums\PlanCode;
use BilliftySDK\SharedResources\Modules\User\Models\Plan;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Factory as AuthFactory;

final class PlanFlowRedirectionService
{
    public function __construct(
        private AuthFactory $auth
    ) {}

	private function user(): ?AuthenticatableContract
    {
        return $this->auth->guard('api')->user(); // or ->guard() if default is correct
    }

    public function pricing()
	{
		if (! $this->user()) {
			// If not logged in
			return FlowRedirectionEnum::KIND_SIGNIN;
		}

		if (! $this->user()?->subscription) {
			return FlowRedirectionEnum::KIND_CHECKOUT;
		}

		return FlowRedirectionEnum::KIND_MANAGE_SUBSCRIPTION;
	}

	public function manageSubscription()
	{
		if (! $this->user()) {
			// If not logged in
			return FlowRedirectionEnum::KIND_SIGNIN;
		}

		if (! $this->user()?->subscription) {
			return FlowRedirectionEnum::KIND_CHECKOUT;
		}

		if ($this->user()?->subscription && $this->user()->plan_id === PlanCode::FREE->planId()) {
			return FlowRedirectionEnum::KIND_CHECKOUT;
		}

		return FlowRedirectionEnum::KIND_BILLING_PORTAL;
	}
}
