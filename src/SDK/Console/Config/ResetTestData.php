<?php

namespace BilliftySDK\SharedResources\SDK\Console\Config;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ResetTestData extends ModularResetTestDataCommand
{
    protected $signature = 'ww:reset 
        {--testonly : Refreshed only the test data and exclude stable data.}';
    protected $description = 'Restart all test data.';

    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        parent::initialize($input, $output);
    }

    protected function process(): array
    {
        return [
           //
        ];
    }

    protected function commandType(): string
    {
        return 'reset';
    }
}
