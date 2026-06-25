<?php

namespace BilliftySDK\SharedResources\Modules\User\Models;


use BilliftySDK\SharedResources\Modules\User\Scopes\ActiveScope;
use Illuminate\Database\Eloquent\Model;

class PlanCapability extends Model
{
    protected $fillable = [
        'plan_id',
        'key',
        'label',
        'description',
        'type',
        'value',
        'meta',
		'group',
		'model_relationship',
		'is_active'
    ];

    protected $casts = [
        'meta' => 'array',
    ];

	protected static function booted(): void
	{
		static::addGlobalScope(new ActiveScope);
	}

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * Cast the raw value according to its type.
     */
    public function getCastValueAttribute()
    {
        return match ($this->type) {
            'bool'   => filter_var($this->value, FILTER_VALIDATE_BOOL),
            'int'    => (int) $this->value,
            'json'   => json_decode($this->value, true),
            default  => $this->value,
        };
    }
}
