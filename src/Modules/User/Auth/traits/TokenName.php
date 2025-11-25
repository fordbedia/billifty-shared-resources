<?php

namespace BilliftySDK\SharedResources\Modules\User\Auth\traits;

use BilliftySDK\SharedResources\Modules\User\Models\User;

trait TokenName
{
	public function getAccessToken(User $user): string
	{
		return $user->createToken('Billifty Web App')->accessToken;
	}
}