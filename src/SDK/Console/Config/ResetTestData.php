<?php

namespace BilliftySDK\SharedResources\SDK\Console\Config;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use BilliftySDK\SharedResources\Modules\Invoicing\Database\Seeders\InvoiceSeeder;
use BilliftySDK\SharedResources\Modules\Invoicing\Database\Seeders\TestInvoiceSeeder;
use BilliftySDK\SharedResources\Modules\User\Database\Seeders\UserSeeder;

class ResetTestData extends ModularResetTestDataCommand
{
    protected $signature = 'billifty:reset 
        {--testonly : Refreshed only the test data and exclude stable data.}';
    protected $description = 'Restart all test data.';

    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        parent::initialize($input, $output);
    }

    protected function process(): array
    {
        return [
					UserSeeder::class,
					InvoiceSeeder::class,
					TestInvoiceSeeder::class,
        ];
    }

    protected function commandType(): string
    {
        return 'reset';
    }
}
