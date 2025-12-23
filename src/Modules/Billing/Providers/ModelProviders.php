<?php

namespace BilliftySDK\SharedResources\Modules\Billing\Providers;

use BilliftySDK\SharedResources\Modules\Billing\Repositories\Eloquents\UserSubscriptionRepository;
use BilliftySDK\SharedResources\Modules\Billing\Repositories\Interfaces\UserSubscriptionInterface;
use BilliftySDK\SharedResources\Modules\Billing\Services\Billing\SubscriptionService;
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
		];
	}
}
