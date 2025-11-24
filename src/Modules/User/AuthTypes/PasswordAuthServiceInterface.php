<?php

namespace BilliftySDK\SharedResources\Modules\User\AuthTypes;

use Illuminate\Http\Request;

interface PasswordAuthServiceInterface
{
	public function login(Request $request): object;
}