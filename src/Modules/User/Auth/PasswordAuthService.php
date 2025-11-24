<?php

namespace BilliftySDK\SharedResources\Modules\User\Auth;

use BilliftySDK\SharedResources\Modules\User\AuthTypes\PasswordAuthServiceInterface;
use BilliftySDK\SharedResources\Modules\User\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PasswordAuthService implements PasswordAuthServiceInterface
{

	public function login(Request $request): object
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            abort(401, 'Invalid credentials');
        }

        $token = $user->createToken('web')->accessToken;

        return (object) [
            'user'  => $user,
            'token' => $token,
        ];
    }
}