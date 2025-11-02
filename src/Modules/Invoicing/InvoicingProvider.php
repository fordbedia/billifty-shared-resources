<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing;

use BilliftySDK\SharedResources\Modules\Invoicing\Providers\InvoiceServiceLayerProvider;
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
}