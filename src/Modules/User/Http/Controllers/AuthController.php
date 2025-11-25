<?php

namespace BilliftySDK\SharedResources\Modules\User\Http\Controllers;

use App\Http\Controllers\Controller;
use BilliftySDK\SharedResources\Modules\User\Auth\GoogleAuthService;
use BilliftySDK\SharedResources\Modules\User\AuthTypes\PasswordAuthServiceInterface;
use BilliftySDK\SharedResources\SDK\Exception\ApiException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
	public function __construct(
		protected PasswordAuthServiceInterface $passwordAuth,
		protected GoogleAuthService            $googleAuth,
	)
	{}

	public function login(Request $request)
    {
		try {
			$result = $this->passwordAuth->login($request);
		}catch (\Throwable $e){
			throw new ApiException('Oops! Something went wrong. Please check your email and password.');
		}

        $user  = $result->user;
        $token = $result->token;

        Auth::login($user, true);

        return response()->json([
            'user'  => $user,
            'token' => $token,
        ]);
    }

    // 2) Google callback login
    public function googleCallback(Request $request)
    {
        $result = $this->googleAuth->handleCallback($request);

        $user  = $result->user;
        $token = $result->token;

        Auth::login($user, true);

        $frontendUrl = config('app.frontend_url');

        return response()->view('user::auth.google-bridge', [
            'token'   => $token,
            'user'    => $user,
            'nextUrl' => $frontendUrl . '/app/invoices',
        ]);
    }

	public function logout(Request $request)
	{
		$user = $request->user();

		$user->token()->revoke();

		return response()->json([
			'message' => 'Successfully logged out',
		]);
	}
}
