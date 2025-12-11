<?php

namespace BilliftySDK\SharedResources\Modules\User\Http\Controllers;

use App\Http\Controllers\Controller;
use BilliftySDK\SharedResources\Modules\User\Http\Requests\UserRequest;
use BilliftySDK\SharedResources\Modules\User\Repository\Contract\UserInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use BilliftySDK\SharedResources\Modules\User\Auth\traits\TokenName;

class UserController extends Controller
{
	use TokenName;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request, UserInterface $repo)
    {
		 $user = $repo->create($request->validated());

		 Auth::login($user, true);

		 $accessToken = $this->getAccessToken($user);

		 return response()->json([
			 'user' => $user,
			 'token' => $accessToken
		 ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

	public function me()
	{
		return Auth::user();
	}
}
