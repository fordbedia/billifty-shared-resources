<?php

namespace BilliftySDK\SharedResources\TestCase\Extras;

use Illuminate\Support\Facades\DB;
use Symfony\Component\Process\Process;

trait RefreshDatabase
{
    protected function refreshDatabase(): void
    {
        // This file is at: src/TestCase/Extras/RefreshDatabase.php
        // So package root is: dirname(__DIR__, 2) => src/TestCase
        $dumpPath = dirname(__DIR__, 1) . '/sqldumps/billifty.pgsql';
        //                 ^ Extras -> TestCase

        // If you prefer clearer:
        // $dumpPath = realpath(__DIR__ . '/../sqldumps/billifty.pgsql');

        if (!$dumpPath || !file_exists($dumpPath)) {
            throw new \RuntimeException('Postgres dump not found at: ' . ($dumpPath ?: '[null]'));
        }

        // For now, don't restore via PDO (pg_dump contains COPY/\.)
        // We'll restore via host script (bin/test-one) or by calling psql/pg_restore.
        // So if you're running "model-only" tests, just return:
        // return;

        // If you still want RefreshDatabase to be a hard requirement for DB tests,
        // keep the path check and implement restore logic later.
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
