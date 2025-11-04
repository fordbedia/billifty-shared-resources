<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Providers;

use BilliftySDK\SharedResources\Modules\Invoicing\Repository\Contracts\BusinessProfileContract;
use BilliftySDK\SharedResources\Modules\Invoicing\Repository\Contracts\ClientsContract;
use BilliftySDK\SharedResources\Modules\Invoicing\Repository\Contracts\InvoiceContracts;
use BilliftySDK\SharedResources\Modules\Invoicing\Repository\Eloquents\BusinessProfileRepository;
use BilliftySDK\SharedResources\Modules\Invoicing\Repository\Eloquents\ClientsRepository;
use BilliftySDK\SharedResources\Modules\Invoicing\Repository\Eloquents\InvoiceRepository;
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
		$this->app->singleton(ClientsContract::class, ClientsRepository::class);
		$this->app->singleton(InvoiceContracts::class, InvoiceRepository::class);
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
				ClientsContract::class,
				InvoiceContracts::class,
			];
		}
}
