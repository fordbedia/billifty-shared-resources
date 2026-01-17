<?php

namespace BilliftySDK\SharedResources\TestCase\Extras;

use Illuminate\Support\Facades\DB;
use Symfony\Component\Process\Process;

trait RefreshDatabase
{
	protected function refreshDatabase(): void
	{
		$dumpPath = dirname(__DIR__, 1) . '/sqldumps/billifty.mysql.sql';

		if (!$dumpPath || !file_exists($dumpPath)) {
			throw new \RuntimeException('MySQL dump not found at: ' . ($dumpPath ?: '[null]'));
		}

		$db   = env('TEST_DB_DATABASE', env('DB_DATABASE', 'app_db'));
		$user = env('TEST_DB_USERNAME', env('DB_USERNAME', 'billifty_u'));
		$pass = env('TEST_DB_PASSWORD', env('DB_PASSWORD', ''));
		$host = env('TEST_DB_HOST', env('DB_HOST', 'mysql'));
		$port = env('TEST_DB_PORT', env('DB_PORT', '3306'));

		$this->restoreMySqlDump($host, $port, $db, $user, $pass, $dumpPath);
	}

	private function restoreMySqlDump(
		string $host,
		string $port,
		string $database,
		string $username,
		string $password,
		string $dumpPathOnDisk
	): void {
		$cmd = [
			'sh', '-lc',
			sprintf(
				'MYSQL_PWD=%s mysql -h %s -P %s -u %s %s < %s',
				escapeshellarg($password),
				escapeshellarg($host),
				escapeshellarg($port),
				escapeshellarg($username),
				escapeshellarg($database),
				escapeshellarg($dumpPathOnDisk),
			),
		];

		$process = new \Symfony\Component\Process\Process($cmd, base_path('../../')); // adjust if needed
		$process->setTimeout(180);
		$process->run();

		if (!$process->isSuccessful()) {
			throw new \RuntimeException("mysql restore failed:\n" . $process->getErrorOutput() . $process->getOutput());
		}
	}


    private function restoreViaDockerComposePostgres(string $database, string $username, string $password, string $dumpPathOnBackend): void
	{
		// This assumes your test runner is executed via docker compose and can call docker.
		// If backend container cannot call docker, then we do the restore from HOST (recommended).
		$cmd = [
			'docker', 'compose',
			'-f', 'docker-compose.yml',
			'-f', 'docker-compose.dev.yml',
			'exec', '-T',
			'postgres',
			'sh', '-lc',
			sprintf(
				'PGPASSWORD=%s psql -U %s -d %s < %s',
				escapeshellarg($password),
				escapeshellarg($username),
				escapeshellarg($database),
				escapeshellarg($dumpPathOnBackend),
			),
		];

		$process = new Process($cmd, base_path('../../')); // adjust working dir if needed
		$process->setTimeout(180);
		$process->run();

		if (!$process->isSuccessful()) {
			throw new \RuntimeException("psql restore failed:\n" . $process->getErrorOutput() . $process->getOutput());
		}
	}
}
