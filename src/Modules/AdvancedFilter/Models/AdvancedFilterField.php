<?php

namespace BilliftySDK\SharedResources\Modules\AdvancedFilter\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AdvancedFilterField extends Model
{
	protected $table = 'advanced_filter_fields';

	protected $casts = [
		'has_sub_fields' => 'boolean',
		'is_enabled' => 'boolean',
		'sort_order' => 'integer',
	];

	public function parent(): BelongsTo
	{
		return $this->belongsTo(
			AdvancedFilterField::class,
			'parent_id'
		);
	}

	public function children(): HasMany
	{
		return $this->hasMany(
			AdvancedFilterField::class,
			'parent_id'
		)->orderBy('sort_order');
	}

	public function fieldOperators(): HasMany
	{
		return $this->hasMany(
			AdvancedFilterFieldOperator::class,
			'advanced_filter_field_id'
		)->orderBy('sort_order');
	}

	public function operators()
	{
		return $this->belongsToMany(
			AdvancedFilterOperator::class,
			'advanced_filter_field_operators',
			'advanced_filter_field_id',
			'advanced_filter_operator_id'
		);
	}
}
