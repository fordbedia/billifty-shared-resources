<?php

namespace BilliftySDK\SharedResources\Modules\AdvancedFilter\Infrastructure\Enums;

enum SqlLogicalOperatorType: string
{
	case WHERE = 'where';

	case WHEREIN = 'where in';

	case RAW = 'raw';

	case WHEREBETWEEN = 'where between';

	public static function fromMethodName(string $name): self
	{
		return match ($name) {
			'where' => self::WHERE,
			'whereIn' => self::WHEREIN,
			'raw' => self::RAW,
			'whereBetween' => self::WHEREBETWEEN,
			default => self::from($name),
		};
	}
}
