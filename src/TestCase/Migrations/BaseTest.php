<?php

namespace BilliftySDK\SharedResources\TestCase\Migrations;

use BilliftySDK\SharedResources\TestCase\BaseTest as PackageBaseTest;
use BilliftySDK\SharedResources\TestCase\Extras\RefreshDatabase;

abstract class BaseTest extends PackageBaseTest
{
	use RefreshDatabase;
}
