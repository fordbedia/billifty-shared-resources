<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Providers;

use BilliftySDK\SharedResources\Modules\Invoicing\Repository\Contracts\BusinessProfileContract;
use BilliftySDK\SharedResources\Modules\Invoicing\Repository\Eloquents\BusinessProfileRepository;
use Illuminate\Support\ServiceProvider;

class InvoiceServiceLayerProvider extends ServiceProvider
{
	protected $defer = true;

    /**
     * Register services.
     */
    public function register(): void
    {
			$this->app->singleton(BusinessProfileContract::class, BusinessProfileRepository::class);
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
				BusinessProfileContract::class,
			];
		}
}
