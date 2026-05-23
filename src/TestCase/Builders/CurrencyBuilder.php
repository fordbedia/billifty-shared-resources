<?php

namespace BilliftySDK\SharedResources\TestCase\Builders;

use BilliftySDK\SharedResources\TestCase\Concerns\CreateCurrencyRecords;

class CurrencyBuilder
{
	use CreateCurrencyRecords;

	public function __construct(
		protected string $code,
		protected string $name,
		protected string $symbol
	) {}

	public static function make(string $code, string $name, string $symbol)
	{
		return new self($code, $name, $symbol);
	}
}