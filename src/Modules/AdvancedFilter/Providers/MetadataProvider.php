<?php

namespace BilliftySDK\SharedResources\Modules\AdvancedFilter\Providers;

use BilliftySDK\SharedResources\Modules\AdvancedFilter\Application\Services\Metadata;
use BilliftySDK\SharedResources\Modules\AdvancedFilter\Http\Controllers\InvoiceAdvancedFilterInputController;
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
    }
}
