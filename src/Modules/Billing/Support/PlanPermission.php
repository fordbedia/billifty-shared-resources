<?php

namespace BilliftySDK\SharedResources\Modules\Billing\Support;

use BilliftySDK\SharedResources\Modules\User\Models\Plan;
use BilliftySDK\SharedResources\Modules\User\Models\PlanCapability;
use BilliftySDK\SharedResources\Modules\User\Models\User;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Str;

class PlanPermission
{
    protected ?User $user = null;

    protected array $aliases = [
        'upload_business_logo' => 'logo_upload',
        'business_logo' => 'logo_upload',
        'logo_upload' => 'logo_upload',
        'custom_brand_colors' => 'custom_branding',
        'custom_branding' => 'custom_branding',
        'custom_invoice_numbering' => 'custom_prefix',
        'invoice_numbering' => 'custom_prefix',
        'templates' => 'templates_tier',
        'invoice_templates' => 'templates_tier',
        'advanced_templates' => 'templates_tier',
        'automated_reminders' => 'automated_reminders',
        'manual_reminders' => 'automated_reminders',
        'online_payments' => 'online_payments',
        'payment_links' => 'online_payments',
        'multi_currency' => 'multi_currency',
        'ai_invoice_assistant' => 'ai_invoice_assistant',
        'analytics' => 'analytics_tier',
        'support' => 'support_level',
        'email_branding' => 'email_branding',
        'pdf_watermark' => 'pdf_watermark',
        'email_watermark' => 'email_watermark',
        'create_business_profile' => 'max_business_profiles',
        'business_profiles' => 'max_business_profiles',
        'create_client' => 'max_clients',
        'clients' => 'max_clients',
        'create_invoice' => 'max_invoices_per_month',
        'invoices' => 'max_invoices_per_month',
        'invoices_per_month' => 'max_invoices_per_month',
    ];

    protected array $inverseAliases = [
        'remove_pdf_watermark' => 'pdf_watermark',
        'no_pdf_watermark' => 'pdf_watermark',
        'pdf_without_watermark' => 'pdf_watermark',
        'remove_email_watermark' => 'email_watermark',
        'no_email_watermark' => 'email_watermark',
        'email_without_watermark' => 'email_watermark',
    ];

    public function __construct(?User $user = null)
    {
        $this->user = $user;
    }

    public static function attempt(User $user): self
    {
        return app(self::class)->forUser($user);
    }

    public function forUser(User $user): self
    {
        $instance = clone $this;
        $instance->user = $user;

        return $instance;
    }

    public function can(string $ability, ?int $currentUsage = null): bool
    {
        $key = $this->normalize($ability);
        $capability = $this->capability($key);

        if (! $capability) {
            return false;
        }

        if ($capability->group === 'limits') {
            return $this->canWithinLimitForKey($key, $currentUsage);
        }

        $allowed = $this->allowsCapabilityValue($capability, $this->get($key));

        return $this->isInverseAbility($ability) ? ! $allowed : $allowed;
    }

    public function has(string $ability): bool
    {
        return $this->can($ability);
    }

    public function get(string $key, mixed $default = null): mixed
    {
        $capability = $this->capability($this->normalize($key));

        if (! $capability) {
            return $default;
        }

        if (
            $capability->type === 'int'
            && (int) $capability->cast_value === 0
            && ($capability->meta['unlimited'] ?? false)
        ) {
            return null;
        }

        return $capability->cast_value;
    }

    public function canWithinLimit(string $limitKey, int $currentUsage): bool
    {
        return $this->canWithinLimitForKey($this->normalize($limitKey), $currentUsage);
    }

    public function currentUsage(string $limitKey): int
    {
        $user = $this->requireUser();
        $key = $this->normalize($limitKey);
        $capability = $this->capability($key);
        $relationName = $this->relationship($key);

        if (! $relationName || ! method_exists($user, $relationName)) {
            return 0;
        }

        $query = $user->{$relationName}();

        if (! $query instanceof Relation) {
            return 0;
        }

        $related = $query->getRelated();
        $createdAtColumn = $related->qualifyColumn($related->getCreatedAtColumn());
        $usageMode = $capability->meta['usage'] ?? null;

        if ($usageMode === 'monthly' || $key === 'max_invoices_per_month') {
            $query->whereBetween($createdAtColumn, [
                now()->startOfMonth(),
                now()->endOfMonth(),
            ]);
        }

        return $query->getQuery()->count();
    }

    public function relationship(string $limitKey): ?string
    {
        return $this->capability($this->normalize($limitKey))?->model_relationship;
    }

    public function toArray(array $context = []): array
    {
        $user = $this->requireUser();
        $plan = $this->plan();

        if (! $plan) {
            return [];
        }

        $capsByGroup = $this->capabilities()->groupBy('group');
        $limits = [];
        $flags = [];
        $allowed = [];

        foreach ($capsByGroup->get('limits', collect()) as $capability) {
            $limits[$capability->key] = $this->valueFor($capability);
            $current = $context['current:' . $capability->model_relationship] ?? $this->currentUsage($capability->key);

            if ($capability->model_relationship) {
                $limits['current:' . $capability->model_relationship] = $current;
                $allowed['create:' . $capability->model_relationship] = $this->canWithinLimit($capability->key, (int) $current);
            }

            $allowed[$capability->key] = $this->canWithinLimit($capability->key, (int) $current);
        }

        foreach ($capsByGroup->get('features', collect()) as $capability) {
            $flags[$capability->key] = $this->valueFor($capability);
            $allowed[$capability->key] = $this->allowsCapabilityValue($capability, $flags[$capability->key]);
        }

        $notAllowed = [];
        foreach ($allowed as $key => $value) {
            $notAllowed[$key] = ! $value;
        }

        return [
            'plan' => [
                'id' => $plan->id,
                'code' => $plan->code,
                'name' => $plan->name,
            ],
            'limits' => $limits,
            'flags' => $flags,
            'allowed' => $allowed,
            'not_allowed' => $notAllowed,
        ];
    }

    protected function canWithinLimitForKey(string $key, ?int $currentUsage = null): bool
    {
        $limit = $this->get($key, null);

        if (is_null($limit)) {
            return true;
        }

        if (! is_int($limit)) {
            return false;
        }

        $currentUsage ??= $this->currentUsage($key);

        return $currentUsage < $limit;
    }

    protected function allowsCapabilityValue(PlanCapability $capability, mixed $value): bool
    {
        if ($capability->type === 'bool') {
            return (bool) $value;
        }

        if ($capability->type === 'string') {
            return ! empty($value) && strtolower((string) $value) !== 'none';
        }

        if ($capability->type === 'int') {
            return is_null($value) || (int) $value > 0;
        }

        return (bool) $value;
    }

    protected function isInverseAbility(string $ability): bool
    {
        $key = $this->normalizeKey($ability);

        return array_key_exists($key, $this->inverseAliases);
    }

    protected function valueFor(PlanCapability $capability): mixed
    {
        if ($capability->type === 'int') {
            return $this->get($capability->key, null);
        }

        return $capability->cast_value;
    }

    protected function capability(string $key): ?PlanCapability
    {
        return $this->capabilities()->firstWhere('key', $key);
    }

    protected function capabilities()
    {
        $plan = $this->plan();

        if (! $plan) {
            return collect();
        }

        if (! $plan->relationLoaded('capabilities')) {
            $plan->load('capabilities');
        }

        return $plan->capabilities;
    }

    protected function plan(): ?Plan
    {
        $user = $this->requireUser();

        if (! $user->relationLoaded('plan')) {
            $user->load('plan.capabilities');
        } elseif ($user->plan && ! $user->plan->relationLoaded('capabilities')) {
            $user->plan->load('capabilities');
        }

        return $user->plan;
    }

    protected function normalize(string $ability): string
    {
        $key = $this->normalizeKey($ability);

        return $this->inverseAliases[$key] ?? $this->aliases[$key] ?? $key;
    }

    protected function normalizeKey(string $ability): string
    {
        return Str::of($ability)->lower()->replace(['-', ' '], '_')->value();
    }

    protected function requireUser(): User
    {
        if (! $this->user) {
            throw new \LogicException('PlanPermission requires a user. Call forUser() or attempt() first.');
        }

        return $this->user;
    }
}
