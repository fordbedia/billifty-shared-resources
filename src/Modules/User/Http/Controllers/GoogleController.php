<?php

namespace BilliftySDK\SharedResources\Modules\User\Http\Controllers;

use App\Http\Controllers\Controller;
use BilliftySDK\SharedResources\Modules\User\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirect(Request $request)
    {
		$next = $request->query('next');

        // For API + SPA, `stateless()` is safer
        return Socialite::driver('google')
            ->stateless()
			->with([
				'state' => base64_encode(json_encode([
                    'next' => $next,
                ])),
			])
            ->redirect();
    }
}
