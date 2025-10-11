<?php

namespace BilliftySDK\SharedResources\SDK\Console\Config;

use Illuminate\Support\Facades\File;

class Make extends ModularMakeCommand
{
    protected $signature = 'ww:make {what} {className}
        {--module= : Module Name}
        {--table= : Name of table to derive model or migration from}
        {--create= : Create a migration file for the model}
        {--model-name= : Model to be targeted for observer}
        {--resource : Turn controller into a formatted API HTTP request}
        {--factory : Factory of the model}';

    protected $description = 'Create Laravel components in modules or the default app paths';

    /**
     * @throws \Exception
     */
    protected function makeInModule(string $command, array $args): int
    {
        // Define the module root directory
        $modulePath = base_path("vendor/weeworxx/shared-resources/src/Modules/{$this->module}");

        // Define subpaths based on component type
        $paths = [
            'make:model'      => 'Models',
            'make:controller' => 'Http/Controllers',
            'make:migration'  => 'Database/Migrations',
            'make:seeder'     => 'Database/Seeders',
            'make:factory'    => 'Database/Factories'
        ];

        $subPath = $paths[$command] ?? 'Misc';
        $fullPath = "{$modulePath}/{$subPath}";

        // Ensure directory exists
        File::ensureDirectoryExists($fullPath);

        // Show in progress
        $this->inProgressInfo();

        // Generate the file manually or call Artisan with a custom path
        $filename =$this->fileName . '.php';
        $targetPath = "{$fullPath}/{$filename}";
        $rawStub = file_get_contents($this->stubPath);
        $compiledStubPath = $this->populateStub($rawStub);

        // For now, just a file stub — you can customize with real stubs later
        if (! file_exists($targetPath)) {
            File::put($targetPath, $compiledStubPath);
            $this->completed("Created <success>{$this->what}</success> in module <success>[{$this->module}]</success>: {$targetPath}");
        } else {
            $this->completed("File already exist at {$this->module}: {$targetPath}");
        }

        return 0;
    }

    /**
     * @param string $stub
     * @return string
     */
    protected function populateStub(string $stub): string
    {
        return str_replace(
            [
                '{{ namespace }}',
                '{{ class }}',
                '{{ rootNamespace }}',
                '{{ table }}',
                '{{ factoryImport }}',
                '{{ factory }}'
            ],
            [
                $this->calculateNamespace(),
                $this->className,
                app()->getNamespace(),
                $this->table ?: $this->create, // fallback if --table is not provided
                $this->factory ? 'use Illuminate\Database\Eloquent\Factories\HasFactory;' : '',
                $this->factory ? 'use HasFactory;' : ''
            ],
            $stub
        );
    }

    /**
     * @return string
     */
    protected function calculateNamespace(): string
    {
        switch($this->what) {
            case 'controller':
                // Example: Vendor\Package\Modules\User\Http\Controllers
                return 'BilliftySDK\\SharedResources\\Modules\\' . $this->module . '\\Http\\Controllers';
                break;
            case 'model':
                return 'BilliftySDK\\SharedResources\\Modules\\' . $this->module . '\\Models';
            case 'factory':
                return 'BilliftySDK\\SharedResources\\Modules\\' . $this->module . '\\Database\\Factories';
            case 'seeder':
                 return 'BilliftySDK\\SharedResources\\Modules\\' . $this->module . '\\Database\\Seeders';
            default:
                return '';
        }
    }

    protected function commandType():string { return 'make'; }
}
