<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing;

use BilliftySDK\SharedResources\Modules\Invoicing\Console\Commands\SendDueInvoicePaymentReminders;
use BilliftySDK\SharedResources\Modules\Invoicing\Models\Invoices;
use BilliftySDK\SharedResources\Modules\Invoicing\Policies\InvoicePolicy;
use BilliftySDK\SharedResources\Modules\Invoicing\Providers\InvoiceServiceLayerProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class InvoicingProvider extends ServiceProvider
{
	protected array $providers = [
		InvoiceServiceLayerProvider::class,
	];

	public function register()
	{
		foreach ($this->providers as $provider) {
			$this->app->register($provider);
		}
	}

	public function boot(): void
    {
        Gate::policy(Invoices::class, InvoicePolicy::class);

		if ($this->app->runningInConsole()) {
			$this->commands([
				SendDueInvoicePaymentReminders::class,
			]);
		}
    }
}
