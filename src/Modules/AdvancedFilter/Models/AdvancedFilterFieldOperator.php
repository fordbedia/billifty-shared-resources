<?php

namespace BilliftySDK\SharedResources\Modules\AdvancedFilter\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdvancedFilterFieldOperator extends Model
{
	protected $table = 'advanced_filter_field_operators';

	protected $casts = [
		'is_default' => 'boolean',
		'sort_order' => 'integer',
	];

	public function field(): BelongsTo
	{
		return $this->belongsTo(
			AdvancedFilterField::class,
			'advanced_filter_field_id'
		);
	}

	public function operator(): BelongsTo
	{
		return $this->belongsTo(
			AdvancedFilterOperator::class,
			'advanced_filter_operator_id'
		);
	}
}
