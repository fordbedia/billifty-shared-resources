<?php

namespace BilliftySDK\SharedResources\TestCase\Command;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class SnapshotTestDatabase extends Command
{
    protected $signature = 'testdb:snapshot
        {--db=app_db : Database name}
        {--user=app_us3r_26!. : DB username}
        {--file=src/TestCase/sqldumps/billifty.pgsql : Output dump path (relative to shared-resources root)}';

    protected $description = 'Create a Postgres SQL dump used by Testbench/RefreshDatabase for fast resets.';

    public function handle(): int
    {
        // IMPORTANT: package root (shared-resources), not base_path() of the host app
        $root = dirname(__DIR__, 3); // .../shared-resources

        $outputRel = (string) $this->option('file');
        $output = $root . DIRECTORY_SEPARATOR . ltrim($outputRel, '/');
        $errFile = preg_replace('/\.pgsql$/', '.dump.err', $output) ?: ($output . '.dump.err');

        @mkdir(dirname($output), 0777, true);

        $db   = (string) $this->option('db');
        $user = (string) $this->option('user');

        $host = env('TEST_DB_HOST', env('DB_HOST', 'postgres'));
        $port = env('TEST_DB_PORT', env('DB_PORT', '5432'));
        $pass = env('TEST_DB_PASSWORD', env('DB_PASSWORD', ''));

        if ($pass === '') {
            $this->error('Missing TEST_DB_PASSWORD/DB_PASSWORD env var (needed for pg_dump).');
            return self::FAILURE;
        }

        // Ensure pg_dump exists in this container
        $check = new Process(['sh', '-lc', 'command -v pg_dump'], $root);
        $check->run();
        if (! $check->isSuccessful()) {
            $this->error('pg_dump not found inside this container.');
            $this->line('Install it in your backend image: postgresql-client (Debian/Ubuntu) or postgresql-client package for Alpine.');
            return self::FAILURE;
        }

        $this->info("Dumping DB '{$db}' from {$host}:{$port} as {$user}...");
        $process = new Process([
            'pg_dump',
            '-h', $host,
            '-p', $port,
            '-U', $user,   // "!" is fine here; no zsh expansion because Symfony Process is not a shell
            '-d', $db,
            '--no-owner',
            '--no-privileges',
        ], $root, [
            'PGPASSWORD' => $pass,
        ], null, 120);

        $process->run();

        file_put_contents($errFile, $process->getErrorOutput() ?: '');

        if (! $process->isSuccessful()) {
            $this->error("pg_dump failed. See: {$errFile}");
            $this->line($process->getErrorOutput());
            return self::FAILURE;
        }

        file_put_contents($output, $process->getOutput());

        $size = @filesize($output) ?: 0;
        $this->info("✅ Dump written: {$output} (" . number_format($size) . " bytes)");
        $this->info("ℹ️  Stderr log: {$errFile}");

        return self::SUCCESS;
    }
}
