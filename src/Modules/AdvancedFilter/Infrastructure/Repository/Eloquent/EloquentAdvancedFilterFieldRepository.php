<?php

namespace BilliftySDK\SharedResources\Modules\AdvancedFilter\Infrastructure\Repository\Eloquent;

use BilliftySDK\SharedResources\Modules\AdvancedFilter\Application\Repository\Ports\AdvancedFilterFieldRepository;
use BilliftySDK\SharedResources\Modules\AdvancedFilter\Infrastructure\Repository\BaseRepository;
use BilliftySDK\SharedResources\Modules\AdvancedFilter\Models\AdvancedFilterField;

class EloquentAdvancedFilterFieldRepository extends BaseRepository implements AdvancedFilterFieldRepository
{
	public function makeModel(): string
	{
		return AdvancedFilterField::class;
	}

	public function module($value)
	{
		return $this->model->whereModule($value)
			->with([
				'fieldOperators.operator',
				'children.fieldOperators.operator',
			])
			->whereNull('parent_id')
			->where('is_enabled', true)
			->orderBy('sort_order')
			->get();
	}
}