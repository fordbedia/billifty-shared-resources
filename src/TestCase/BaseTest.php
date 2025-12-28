<?php

namespace BilliftySDK\SharedResources\TestCase;

use BilliftySDK\SharedResources\SharedResourceServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class BaseTest extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            SharedResourceServiceProvider::class,
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();

        // Only execute if the test class uses the RefreshDatabase trait
        if (method_exists($this, 'refreshDatabase')) {
            $this->refreshDatabase();
        }
    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('database.default', 'testing');

        // Prefer env so you don't hardcode credentials in tests
        $app['config']->set('database.connections.testing', [
            'driver' => 'pgsql',
            'host' => env('TEST_DB_HOST', env('DB_HOST', 'postgres')),
            'port' => env('TEST_DB_PORT', env('DB_PORT', '5432')),
            'database' => env('TEST_DB_DATABASE', env('DB_DATABASE', 'app_db')),
            'username' => env('TEST_DB_USERNAME', env('DB_USERNAME', 'postgres')),
            'password' => env('TEST_DB_PASSWORD', env('DB_PASSWORD', '')),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            'search_path' => 'public',
            'sslmode' => 'prefer',
        ]);
    }
}
