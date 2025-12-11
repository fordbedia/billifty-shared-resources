<?php

namespace BilliftySDK\SharedResources\Modules\User\Models;


use Illuminate\Database\Eloquent\Model;

class PlanCapability extends Model
{
    protected $fillable = [
        'plan_id',
        'key',
        'label',
        'type',
        'value',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
    ];

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
