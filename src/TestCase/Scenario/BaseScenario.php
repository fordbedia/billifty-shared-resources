<?php

namespace BilliftySDK\SharedResources\TestCase\Scenario;

abstract class BaseScenario
{
	public function __construct(
		protected string $status = 'draft',
		protected string $planType = 'free',
		protected string $category = 'modern',
		protected string $template = 'moderno',
		protected string $colorScheme = 'ocean',
		protected string $currency = 'USD',
	)
	{}

	public static function make(...$arguments): mixed
	{
		return (new static(...$arguments))->handle();
	}

	public function __invoke(): mixed
	{
		return $this->handle();
	}

	abstract public function handle(): mixed;

	public function collect(array $data)
	{
		return TestScenarioCollection::make($data);
	}
}
