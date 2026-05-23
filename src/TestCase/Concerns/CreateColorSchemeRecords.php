<?php

namespace BilliftySDK\SharedResources\TestCase\Concerns;

use BilliftySDK\SharedResources\Modules\Invoicing\Models\ColorScheme;

trait CreateColorSchemeRecords
{
	public function createColorSchemeOcean()
	{
		return ColorScheme::create([
			'color_scheme_name' => 'Ocean Blue',
			'slug' => 'ocean',
			'preview_url' => '/images/invoice-selection/ocean-blue.png'
		]);
	}

	public function createColorSchemeForest()
	{
		return ColorScheme::create([
			'color_scheme_name' => 'Forest Green',
			'slug' => 'forest',
			'preview_url' => '/images/invoice-selection/forest-green.png'
		]);
	}

	public function createColorSchemeRoyal()
	{
		return ColorScheme::create([
			'color_scheme_name' => 'Royal Purple',
			'slug' => 'royal',
			'preview_url' => '/images/invoice-selection/royal-purple.png'
		]);
	}

	public function createColorSchemeCrimson()
	{
		return ColorScheme::create([
			'color_scheme_name' => 'Crimson Red',
			'slug' => 'crimson',
			'preview_url' => '/images/invoice-selection/crimson-red.png'
		]);
	}

	public function createColorSchemeSunset()
	{
		return ColorScheme::create([
			'color_scheme_name' => 'Sunset Orange',
			'slug' => 'sunset',
			'preview_url' => '/images/invoice-selection/sunset-orange.png'
		]);
	}
}