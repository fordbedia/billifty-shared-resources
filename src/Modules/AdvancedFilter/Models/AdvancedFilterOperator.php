<?php

namespace BilliftySDK\SharedResources\Modules\AdvancedFilter\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AdvancedFilterOperator extends Model
{
	protected $table = 'advanced_filter_operators';

	protected $casts = [
		'requires_value' => 'boolean',
		'is_enabled' => 'boolean',
		'sort_order' => 'integer',
	];

	public function fieldOperators(): HasMany
	{
		return $this->hasMany(
			AdvancedFilterFieldOperator::class,
			'advanced_filter_operator_id'
		)->orderBy('sort_order');
	}

	public function fields(): BelongsToMany
	{
		return $this->belongsToMany(
			AdvancedFilterField::class,
			'advanced_filter_field_operators',
			'advanced_filter_operator_id',
			'advanced_filter_field_id'
		)
			->withPivot([
				'is_default',
				'placeholder',
				'value_source',
				'sort_order',
			])
			->withTimestamps()
			->orderBy('advanced_filter_field_operators.sort_order');
	}
}
