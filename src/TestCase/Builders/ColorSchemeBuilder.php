<?php

namespace BilliftySDK\SharedResources\TestCase\Builders;

use BilliftySDK\SharedResources\TestCase\Concerns\CreateColorSchemeRecords;

class ColorSchemeBuilder
{
	use CreateColorSchemeRecords;

	public static function make()
	{
		return new self;
	}
}