<?php

namespace BilliftySDK\SharedResources\Modules\User\Repository\Eloquent;

use BilliftySDK\SharedResources\Modules\User\Models\User;
use BilliftySDK\SharedResources\Modules\User\Repository\UserBaseRepository;
use BilliftySDK\SharedResources\Modules\User\Repository\Contract\UserInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class UserRepository extends UserBaseRepository implements UserInterface
{

	public function makeModel(): string
	{
		return User::class;
	}

	public function getUserByProvider(string $provider, string $providerId)
	{
		$user = $this->model;

		if ($provider === 'google') {
			$user = $user->where('provider_id', $providerId);
		}

		return $user->first();
	}

	public function getUserByEmail(string $email): ?Model
	{
		return $this->model->where('email', $email)->first();
	}

	public function provider(): ?Model
	{
		return $this->getModelByAuthUser()->pluck('provider')->first() ?? null;
	}

	public function isProviderGoogle(): bool
	{
		return $this->getModelByAuthUser()
			->where('provider', 'google')
			->exists();
	}

	public function updatePlan(int $planId): bool
	{
		return $this->getModelByAuthUser()->update(['plan_id' => $planId]);
	}

	public function authUser(): ?Model
	{
		return $this->getModelByAuthUser()->first();
	}
}