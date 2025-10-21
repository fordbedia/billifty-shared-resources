<?php

namespace BilliftySDK\SharedResources;

use BilliftySDK\SharedResources\SDK\Console\Config\Make;
use BilliftySDK\SharedResources\SDK\Console\Config\ResetTestData;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\File;

class SharedResourceServiceProvider extends ServiceProvider
{
    protected array $providers = [
       //
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
            ]);
        }
    }

    protected function loadModules()
    {
        $modulesPath = __DIR__ . '/Modules';

        if (!is_dir($modulesPath)) {
            return;
        }

        $moduleDirs = File::directories($modulesPath);

        foreach ($moduleDirs as $moduleDir) {
						$moduleName = basename($moduleDir); // e.g., "Invoicing"
            // ==============================================================
            // Load Route Path: web and api.php
            // ==============================================================
            $web = $moduleDir . '/routes/web.php';
            if (file_exists($web)) {
                $this->loadRoutesFrom($web);
            }
            $api = $moduleDir . '/routes/api.php';
            if (file_exists($api)) {
                Route::prefix('api')
                     ->middleware('api')
                     // optionally constrain namespace so your controllers resolve
                     ->namespace('BilliftySDK\\SharedResources\\Modules\\'
                                . basename($moduleDir)
                                . '\\Http\\Controllers')
                     ->group($api);
            }
            // Migrations
            $migrationPath = $moduleDir . '/Database/Migrations';
            if (is_dir($migrationPath)) {
                $this->loadMigrationsFrom($migrationPath);
            }

						/**
             * ==============================================================
             * VIEWS
             * ==============================================================
             * Allow calling view('invoicing::template') from main app
             * Points to /Modules/Invoicing/resources/views
             */
            $viewPath = $moduleDir . '/resources/views';
            if (is_dir($viewPath)) {
                // Lowercase namespace for consistency: e.g., invoicing::...
                $namespace = strtolower($moduleName);
                $this->loadViewsFrom($viewPath, $namespace);
            }

						/**
             * ==============================================================
             * CONFIG / TRANSLATIONS (optional)
             * ==============================================================
             * If modules contain config or lang folders, you can load them here too.
             */
            $configPath = $moduleDir . '/config';
            if (is_dir($configPath)) {
                foreach (File::files($configPath) as $file) {
                    $this->mergeConfigFrom($file->getRealPath(), pathinfo($file->getFilename(), PATHINFO_FILENAME));
                }
            }

            $langPath = $moduleDir . '/resources/lang';
            if (is_dir($langPath)) {
                $this->loadTranslationsFrom($langPath, strtolower($moduleName));
            }

            // You can also auto-load views per module, config, etc.
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

