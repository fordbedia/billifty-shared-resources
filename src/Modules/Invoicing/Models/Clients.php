<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class Clients extends Model
{
	use SoftDeletes;

	protected $table = 'clients';
	protected $guarded = [];

	public function workspace()
	{
		return $this->belongsTo(Workspace::class, 'workspace_id');
	}

	public function scopeForWorkspace(Builder $query, int $workspaceId): Builder
	{
		return $query->where('workspace_id', $workspaceId);
	}

	public function scopeForUser(Builder $query, int $userId): Builder
	{
		// Ownership is resolved through workspace.user_id; clients no longer store user_id.
		return $query->whereHas('workspace', function (Builder $workspaceQuery) use ($userId): void {
			$workspaceQuery->where('user_id', $userId);
		});
	}
}
