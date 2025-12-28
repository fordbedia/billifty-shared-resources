<?php

namespace BilliftySDK\SharedResources;

use BilliftySDK\SharedResources\Modules\Billing\BillingProvider;
use BilliftySDK\SharedResources\Modules\Invoicing\InvoicingProvider;
use BilliftySDK\SharedResources\Modules\User\UserProvider;
use BilliftySDK\SharedResources\SDK\Console\Config\Make;
use BilliftySDK\SharedResources\SDK\Console\Config\ResetTestData;
use BilliftySDK\SharedResources\TestCase\Command\SnapshotTestDatabase;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\File;

class SharedResourceServiceProvider extends ServiceProvider
{
    protected array $providers = [
		InvoicingProvider::class,
		UserProvider::class,
		BillingProvider::class,
    ];

    public function boot()
    {
        // Load module routes and migrations dynamically
        $this->loadModules();

        // Optional: Load SDK services, views, etc.
        // $this->loadViewsFrom(__DIR__ . '/SDK/resources/views', 'sdk');

        if ($this->app->runningInConsole()) {
            $this->commands([
                Make::class,
                ResetTestData::class,
				SnapshotTestDatabase::class
            ]);
        }
    }

    protected function loadModules()
	{
		$modulesPath = __DIR__ . '/Modules';

		if (! is_dir($modulesPath)) {
			return;
		}

		$moduleDirs = File::directories($modulesPath);

		// In PHPUnit/Testbench we usually do not want routes auto-loaded
		$shouldLoadRoutes = ! $this->app->environment('testing');

		foreach ($moduleDirs as $moduleDir) {
			$moduleName = basename($moduleDir); // e.g., "Invoicing"

			// ==============================================================
			// ROUTES (skip in testing)
			// ==============================================================
			if ($shouldLoadRoutes) {
				$web = $moduleDir . '/routes/web.php';
				if (file_exists($web)) {
					$this->loadRoutesFrom($web);
				}

				$api = $moduleDir . '/routes/api.php';
				if (file_exists($api)) {
					Route::prefix('api')
						->middleware('api')
						->namespace('BilliftySDK\\SharedResources\\Modules\\'
							. $moduleName
							. '\\Http\\Controllers')
						->group($api);
				}
			}

			// ==============================================================
			// MIGRATIONS
			// ==============================================================
			$migrationPath = $moduleDir . '/Database/Migrations';
			if (is_dir($migrationPath)) {
				$this->loadMigrationsFrom($migrationPath);
			}

			// ==============================================================
			// VIEWS
			// ==============================================================
			$viewPath = $moduleDir . '/resources/views';
			if (is_dir($viewPath)) {
				$namespace = strtolower($moduleName);
				$this->loadViewsFrom($viewPath, $namespace);
			}

			// ==============================================================
			// CONFIG
			// ==============================================================
			$configPath = $moduleDir . '/config';
			if (is_dir($configPath)) {
				foreach (File::files($configPath) as $file) {
					$this->mergeConfigFrom(
						$file->getRealPath(),
						pathinfo($file->getFilename(), PATHINFO_FILENAME)
					);
				}
			}

			// ==============================================================
			// TRANSLATIONS
			// ==============================================================
			$langPath = $moduleDir . '/resources/lang';
			if (is_dir($langPath)) {
				$this->loadTranslationsFrom($langPath, strtolower($moduleName));
			}
		}
	}

    public function register()
    {
        // ==============================================================
        // Register all providers
        // ==============================================================
        foreach($this->providers as $provider) {
            $this->app->register($provider);
        }
    }
}

