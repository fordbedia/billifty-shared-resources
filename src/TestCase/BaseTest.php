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

        // Restore the snapshot dump only when the test opts in with
        // BilliftySDK\SharedResources\TestCase\Extras\RefreshDatabase.
        if (method_exists($this, 'refreshDatabase')) {
            $this->refreshDatabase();
        }
    }

	protected function getEnvironmentSetUp($app): void
	{
		$testingDatabase = env('BILLIFTY_MYSQL_TEST_DB_DATABASE', 'billifty_test');
		$applicationDatabase = env('DB_DATABASE');

		$this->assertTestingDatabaseIsSafe($testingDatabase, $applicationDatabase);

		$app['config']->set('database.default', 'testing');
		$app['config']->set('database.connections.testing', [
			'driver' => 'mysql',
			'host' => env('BILLIFTY_MYSQL_TEST_DB_HOST', 'mysql'),
			'port' => env('BILLIFTY_MYSQL_TEST_DB_PORT', '3306'),
			'database' => $testingDatabase,
			'username' => env('BILLIFTY_MYSQL_TEST_DB_USERNAME', 'billifty_u'),
			'password' => env('BILLIFTY_MYSQL_TEST_DB_PASSWORD', 'b1ll1iftykamikalara0213'),

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

	private function assertTestingDatabaseIsSafe(string $testingDatabase, ?string $applicationDatabase): void
	{
		if ($testingDatabase === '') {
			throw new \RuntimeException('BILLIFTY_MYSQL_TEST_DB_DATABASE must name a dedicated test database.');
		}

		if ($applicationDatabase !== null && $testingDatabase === $applicationDatabase) {
			throw new \RuntimeException(
				"Refusing to run tests against application database [{$testingDatabase}]. " .
				'Set BILLIFTY_MYSQL_TEST_DB_DATABASE to a dedicated test database.'
			);
		}

		if (!str_contains(strtolower($testingDatabase), 'test')) {
			throw new \RuntimeException(
				"Refusing to run tests against database [{$testingDatabase}] because its name does not contain [test]."
			);
		}
	}

}
