<?php

namespace BilliftySDK\SharedResources\Modules\User\Models;


use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
	protected $table = 'plans';
    protected $fillable = [
        'code',
        'name',
        'description',
        'price_monthly',
        'price_yearly',
        'max_business_profiles',
        'max_clients',
        'max_invoices_per_month',
        'pdf_watermark',
        'email_watermark',
        'allows_online_payments',
        'allows_automated_reminders',
        'is_default',
        'sort_order',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

	public function capabilities()
    {
        return $this->hasMany(PlanCapability::class);
    }

	/**
     * Get a capability model by key.
     */
    public function capability(string $key): ?PlanCapability
    {
        // assumes you eager-load capabilities where possible
        return $this->capabilities->firstWhere('key', $key);
    }

    /**
     * Get a capability as a typed PHP value, with default.
     */
    public function capabilityValue(string $key, mixed $default = null): mixed
    {
        $cap = $this->capability($key);

        return $cap?->cast_value ?? $default;
    }

    public function capabilityInt(string $key, ?int $default = null): ?int
    {
        $val = $this->capabilityValue($key, $default);

        // treat 0 + meta['unlimited'] = unlimited (null)
        $cap = $this->capability($key);

        if (
            $cap &&
            $cap->type === 'int' &&
            (int) $cap->value === 0 &&
            ($cap->meta['unlimited'] ?? false)
        ) {
            return null;
        }

        return is_int($val) ? $val : $default;
    }

    public function capabilityBool(string $key, bool $default = false): bool
    {
        $val = $this->capabilityValue($key, $default);
        return (bool) $val;
    }

    public function capabilityString(string $key, ?string $default = null): ?string
    {
        $val = $this->capabilityValue($key, $default);
        return is_string($val) ? $val : $default;
    }

    /**
     * Get all capabilities as a simple [key => value] array.
     */
    public function capabilitiesArray(): array
    {
        return $this->capabilities
            ->mapWithKeys(fn ($cap) => [$cap->key => $cap->cast_value])
            ->toArray();
    }
}
