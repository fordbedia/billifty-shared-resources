<?php

namespace BilliftySDK\SharedResources\TestCase\Concerns;

use BilliftySDK\SharedResources\Modules\Invoicing\Models\Currency;

trait CreateCurrencyRecords
{
	public function create(
		?string $code = null,
		?string $name = null,
		?string $symbol = null
	): Currency {
		return Currency::create([
			'code' => $code ?? $this->code,
			'name' => $name ?? $this->name,
			'symbol' => $symbol ?? $this->symbol,
			'precision' => 2,
			'is_active' => 1
		]);
	}
}