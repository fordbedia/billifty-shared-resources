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

		$app['config']->set('database.connections.testing', [
			'driver' => 'mysql',
			'host' => env('TEST_DB_HOST', env('DB_HOST', 'mysql')),
			'port' => env('TEST_DB_PORT', env('DB_PORT', '3306')),
			'database' => env('TEST_DB_DATABASE', env('DB_DATABASE', 'app_db')),
			'username' => env('TEST_DB_USERNAME', env('DB_USERNAME', 'billifty_u')),
			'password' => env('TEST_DB_PASSWORD', env('DB_PASSWORD', 'b1ll1iftykamikalara0213')),

			'charset' => 'utf8mb4',
			'collation' => 'utf8mb4_unicode_ci',
			'prefix' => '',
			'prefix_indexes' => true,
			'strict' => true,
			'engine' => 'InnoDB',
			'options' => extension_loaded('pdo_mysql') ? array_filter([
				// optional, prevents “server has gone away” for some dumps
				\PDO::MYSQL_ATTR_INIT_COMMAND => "SET sql_mode='STRICT_TRANS_TABLES'",
			]) : [],
		]);
	}

}
