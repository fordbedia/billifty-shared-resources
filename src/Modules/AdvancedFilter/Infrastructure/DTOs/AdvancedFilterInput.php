<?php

namespace BilliftySDK\SharedResources\Modules\AdvancedFilter\Infrastructure\DTOs;

final readonly class AdvancedFilterInput
{
	/** @param AdvancedFilterGroup[] $groups */
	public function __construct(public array $groups)
	{
	}

	public static function fromArray(array $data): self
	{
		return new self(
			groups: collect($data['groups'] ?? [])
				->map(fn($group) => AdvancedFilterGroup::fromArray($group))
				->all()
		);
	}
}