<?php

namespace BilliftySDK\SharedResources\TestCase\Extras;

trait RefreshDatabase
{
	protected function refreshDatabase(): void
	{
		$root = dirname(__DIR__, 2);
		$dumpRelPath = env('TEST_DB_SNAPSHOT_FILE', 'src/TestCase/sqldumps/billifty.mysql.sql');
		$dumpPath = $root . '/' . ltrim($dumpRelPath, '/');

		if (!$dumpPath || !file_exists($dumpPath)) {
			throw new \RuntimeException(
				'MySQL dump not found at: ' . ($dumpPath ?: '[null]') .
				'. Generate it with `php artisan testdb:snapshot` from the backend container.'
			);
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

}
