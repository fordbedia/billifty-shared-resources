<?php

namespace BilliftySDK\SharedResources\Modules\AdvancedFilter\Infrastructure\Exceptions;

use InvalidArgumentException;

final class InvalidAdvancedFilterInputException extends InvalidArgumentException
{
	public static function invalidValueType(string $field, string $expectedType, mixed $value): self
	{
		return new self(sprintf(
			"Invalid advanced filter value for field [%s]. Expected [%s], got [%s].",
			$field,
			$expectedType,
			get_debug_type($value),
		));
	}
}
