<?php

namespace BilliftySDK\SharedResources\Modules\AdvancedFilter\Providers;

use BilliftySDK\SharedResources\Modules\AdvancedFilter\Application\Services\Metadata;
use BilliftySDK\SharedResources\Modules\AdvancedFilter\Http\Controllers\InvoiceAdvancedFilterInputController;
use BilliftySDK\SharedResources\Modules\AdvancedFilter\Infrastructure\Engines\InvoiceQueryEngine;
use BilliftySDK\SharedResources\Modules\AdvancedFilter\Infrastructure\Engines\QueryEngine;
use Illuminate\Support\ServiceProvider;

class MetadataProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {

    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->app->when(InvoiceAdvancedFilterInputController::class)
			->needs(Metadata::class)
			->give(fn($app) => new Metadata('invoices'));

		$this->app->when(InvoiceAdvancedFilterInputController::class)
			->needs(QueryEngine::class)
			->give(fn($app) => $app->make(InvoiceQueryEngine::class));
    }
}
