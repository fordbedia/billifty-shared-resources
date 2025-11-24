<?php

namespace BilliftySDK\SharedResources\Modules\User\Auth;

use BilliftySDK\SharedResources\Modules\User\AuthTypes\GoogleAuthServiceInterface;
use BilliftySDK\SharedResources\Modules\User\Repository\Contract\UserInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthService implements GoogleAuthServiceInterface
{
	public function __construct(protected UserInterface $user)
	{
	}

	public function handleCallback(Request $request): object
	{
		 // Get user info from Google
        $googleUser = Socialite::driver('google')
            ->stateless()
            ->user();

        // 1) Try match by provider_id (already linked)
		$user = $this->user->getUserByProvider('google', $googleUser->getId());

        // 2) Or match by email (existing account, first time with Google)
        if (! $user && $googleUser->getEmail()) {
			$user = $this->user->getUserByEmail($googleUser->getEmail());
        }

        // 3) Register new user if none found
        if (! $user) {
			$user = $this->user->create([
                'name'        => $googleUser->getName() ?: $googleUser->getNickname() ?: 'Google User',
                'email'       => $googleUser->getEmail(),
                'password'    => bcrypt(Str::random(32)), // random, since Google handles auth
                'provider'    => 'google',
                'provider_id' => $googleUser->getId(),
                'avatar'      => $googleUser->getAvatar(),
            ]);
        } else {
            // Ensure provider info is saved once they use Google
            $user->update([
                'provider'    => 'google',
                'provider_id' => $user->provider_id ?: $googleUser->getId(),
                'avatar'      => $user->avatar ?: $googleUser->getAvatar(),
            ]);
        }

        // If you’re building an SPA with Passport, you might instead return a token:
         $token = $user->createToken('web')->accessToken;

         $frontendUrl = config('app.frontend_url');

		// Return a tiny HTML page that will write to localStorage
		return (object) [
			'user'  => $user,
            'token' => $token,
		];
	}
}