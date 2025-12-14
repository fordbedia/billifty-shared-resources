<?php

namespace BilliftySDK\SharedResources\Modules\User\Providers;

use BilliftySDK\SharedResources\Modules\User\Scopes\ActiveScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
		Builder::macro('withInactive', function () {
			return $this->withoutGlobalScope(ActiveScope::class);
		});

		Builder::macro('onlyInactive', function() {
			return $this->withoutGlobalScope(ActiveScope::class)
				->where('is_active', false);
		});
    }
}
