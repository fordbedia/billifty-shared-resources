<?php

namespace BilliftySDK\SharedResources\Modules\AdvancedFilter;

use BilliftySDK\SharedResources\Modules\AdvancedFilter\Application\Repository\Ports\AdvancedFilterOptionRepository;
use BilliftySDK\SharedResources\Modules\AdvancedFilter\Infrastructure\Repository\Eloquent\EloquentAdvancedFilterOptionRepository;
use BilliftySDK\SharedResources\Modules\AdvancedFilter\Providers\MetadataProvider;
use Illuminate\Support\ServiceProvider;

class AdvancedFilterProvider extends ServiceProvider
{
    protected array $providers = [
        MetadataProvider::class
    ];

    public function register(): void
    {
		$this->app->bind(AdvancedFilterOptionRepository::class, EloquentAdvancedFilterOptionRepository::class);

        foreach ($this->providers as $provider) {
            $this->app->register($provider);
        }
    }

    public function boot(): void
    {
        //
    }
}
