<?php

namespace BilliftySDK\SharedResources\Modules\User;

use BilliftySDK\SharedResources\Modules\Invoicing\Models\Invoices;
use BilliftySDK\SharedResources\Modules\Invoicing\Policies\InvoicePolicy;
use BilliftySDK\SharedResources\Modules\User\Providers\UserServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class UserProvider extends ServiceProvider
{
	protected array $providers = [
		UserServiceProvider::class,
	];

	protected array $policies = [
        Invoices::class => InvoicePolicy::class,
    ];

	public function register()
	{
		foreach ($this->providers as $provider) {
			$this->app->register($provider);
		}
	}

	public function boot(): void
    {
        foreach ($this->policies as $model => $policy) {
            Gate::policy($model, $policy);
        }
    }
}