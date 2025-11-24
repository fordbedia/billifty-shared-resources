<?php

namespace BilliftySDK\SharedResources\Modules\User\Repository\Eloquent;

use BilliftySDK\SharedResources\Modules\User\Models\User;
use BilliftySDK\SharedResources\Modules\User\Repository\UserBaseRepository;
use BilliftySDK\SharedResources\Modules\User\Repository\Contract\UserInterface;
use Illuminate\Database\Eloquent\Model;

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
		return $this->getByUser()->pluck('provider')->first() ?? null;
	}

	public function isProviderGoogle(): bool
	{
		return $this->getByUser()
			->where('provider', 'google')
			->exists();
	}
}