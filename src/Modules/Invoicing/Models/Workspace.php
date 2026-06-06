<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Models;


use BilliftySDK\SharedResources\Modules\User\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Workspace extends Model
{
    protected $table = 'workspace';
	protected $guarded = [];

	protected $casts = [
		'is_active' => 'bool',
		'is_default' => 'bool',
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'user_id');
	}

	public function invoices()
	{
		return $this->hasMany(Invoices::class, 'workspace_id', 'id');
	}

	public function businessProfiles()
	{
		return $this->hasMany(BusinessProfiles::class, 'workspace_id', 'id');
	}

	public function clients()
	{
		return $this->hasMany(Clients::class, 'workspace_id', 'id');
	}

	public function scopeForUser(Builder $query, int $userId): Builder
	{
		return $query->where('user_id', $userId);
	}

	public function scopeDefault(Builder $query): Builder
	{
		return $query->where('is_default', 1);
	}

	public static function ensureDefaultForUser(User|int $user): self
	{
		$userId = $user instanceof User ? (int) $user->getKey() : (int) $user;

		$existingDefault = static::query()
			->forUser($userId)
			->default()
			->first();

		if ($existingDefault) {
			return $existingDefault;
		}

		$fallbackWorkspace = static::query()
			->forUser($userId)
			->orderByDesc('is_default')
			->orderBy('id')
			->first();

		if ($fallbackWorkspace) {
			$fallbackWorkspace->forceFill([
				'name' => $fallbackWorkspace->name ?: 'default',
				'is_active' => 1,
				'is_default' => 1,
			])->save();

			return $fallbackWorkspace;
		}

		return static::query()->create([
			'user_id' => $userId,
			'name' => 'default',
			'is_active' => 1,
			'is_default' => 1,
		]);
	}
}
