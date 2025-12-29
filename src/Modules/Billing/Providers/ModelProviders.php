<?php

namespace BilliftySDK\SharedResources\Modules\Billing\Providers;

use BilliftySDK\SharedResources\Modules\Billing\Models\UserSubscription;
use BilliftySDK\SharedResources\Modules\Billing\Repositories\Eloquents\UserSubscriptionRepository;
use BilliftySDK\SharedResources\Modules\Billing\Repositories\Interfaces\UserSubscriptionInterface;
use BilliftySDK\SharedResources\Modules\Billing\Services\Billing\SubscriptionService;
use BilliftySDK\SharedResources\Modules\Billing\Services\PlanFlowRedirectionService;
use BilliftySDK\SharedResources\Modules\User\Repository\Eloquent\UserRepository;
use Illuminate\Support\ServiceProvider;

class ModelProviders extends ServiceProvider
{
	protected bool $defer = true;

    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(UserSubscriptionInterface::class, UserSubscriptionRepository::class);

		$this->app->bind(SubscriptionService::class, fn() => new SubscriptionService(request(), new UserSubscriptionRepository, new UserRepository()));

		$this->app->singleton('billifty.plan_flow_redirection', function ($app) {
            return new PlanFlowRedirectionService($app['auth']);
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }

	public function provides(): array
	{
		return [
			UserSubscriptionInterface::class,
			SubscriptionService::class,
        	'plan-flow-redirection',
		];
	}
}
