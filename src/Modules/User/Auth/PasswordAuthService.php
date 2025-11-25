<?php

namespace BilliftySDK\SharedResources\Modules\User\Auth;

use BilliftySDK\SharedResources\Modules\User\AuthTypes\PasswordAuthServiceInterface;
use BilliftySDK\SharedResources\Modules\User\Models\User;
use BilliftySDK\SharedResources\Modules\User\Repository\Contract\UserInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;

class PasswordAuthService implements PasswordAuthServiceInterface
{
	public function __construct(protected UserInterface $user) {}

	public function login(Request $request): object
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

		$user = $this->user->getUserByEmail($credentials['email']);

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            abort(401, 'Invalid credentials');
        }

        $token = $user->createToken('Billifty Web App')->accessToken;

        return (object) [
            'user'  => $user,
            'token' => $token,
        ];
    }
}