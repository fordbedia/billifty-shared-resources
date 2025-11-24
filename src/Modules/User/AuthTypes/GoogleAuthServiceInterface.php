<?php

namespace BilliftySDK\SharedResources\Modules\User\AuthTypes;

use Illuminate\Http\Request;

interface GoogleAuthServiceInterface
{
	public function handleCallback(Request $request): object;
}