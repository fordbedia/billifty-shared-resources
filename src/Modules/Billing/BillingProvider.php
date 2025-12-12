<?php

namespace BilliftySDK\SharedResources\Modules\Billing;

use BilliftySDK\SharedResources\Modules\Billing\Providers\StripeProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class BillingProvider extends ServiceProvider
{
	protected array $providers = [
		StripeProvider::class,
	];

	protected array $policies = [
        //
    ];

	public function register()
	{
		foreach ($this->providers as $provider) {
			$this->app->register($provider);
		}
	}

	public function boot(): void
    {
        //
    }
}