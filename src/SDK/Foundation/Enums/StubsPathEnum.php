<?php

namespace BilliftySDK\SharedResources\SDK\Foundation\Enums;

enum StubsPathEnum: string
{
    case MIGRATION = 'migration.';
    case CONTROLLER = 'controller.';
    case MODEL = 'model.';
    case FACTORY = 'factory.';
    case SEEDER = 'seeder.';

    public function getFullPath()
    {
        return '/vendor/billifty/shared-resources/src/SDK/Foundation/stubs/' . $this->value;
    }
}
