<?php

namespace BilliftySDK\SharedResources\Modules\User\Http\Controllers;

use App\Http\Controllers\Controller;
use BilliftySDK\SharedResources\Modules\Billing\Services\Billing\SubscriptionService;
use BilliftySDK\SharedResources\Modules\Invoicing\Adapters\Outbound\ProfileImageUploadAdapter;
use BilliftySDK\SharedResources\Modules\User\Http\Requests\ChangePasswordRequest;
use BilliftySDK\SharedResources\Modules\User\Http\Requests\UserRequest;
use BilliftySDK\SharedResources\Modules\User\Http\Resources\UserJsonResource;
use BilliftySDK\SharedResources\Modules\User\Repository\Contract\UserInterface;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use BilliftySDK\SharedResources\Modules\User\Auth\traits\TokenName;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
	use TokenName;

	public function __construct(
		protected UserInterface $user,
		protected SubscriptionService $subscriptionService
	) {}

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
		return DB::transaction(function () use ($request, $repo) {
			$user = $repo->create($request->validated());

			Auth::login($user, true);

			$accessToken = $this->getAccessToken($user);

			$next = $this->subscriptionService->decodeCallback($request);
			$url =  config('services.stripe.return_url');
			$nextUrl = $this->subscriptionService->handle($url, $next);
			$user->sendEmailVerificationNotification();

			 return response()->json([
				 'user' => $user,
				 'token' => $accessToken,
				 'nextUrl' => $nextUrl
			 ]);
		});
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
    public function update(
		Request $request,
		int $id,
		UserInterface $userRepo
	) {
		$avatar = $userRepo->authUser()->avatar;
		$uploader = ProfileImageUploadAdapter::make('public');
		if ($request->has('avatar') && $request->file('avatar') && $request->file('avatar') instanceof UploadedFile) {
			['logo_path' => $avatar] = $uploader->store($request->file('avatar'));
		}

		if ($userRepo->authUser()->avatar) {
			$uploader->deleteLast($userRepo->authUser(), 'avatar');
		}

        return $userRepo->updateUser(array_merge($request->all(), ['avatar' => $avatar]));
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
		$user = Auth::user();

		if (! $user) {
			return response()->json([
				'message' => 'Unauthenticated.',
			], 401);
		}

		$user->loadMissing('plan.capabilities', 'subscription');

		return new UserJsonResource($user);
	}

	public function changePassword(
		ChangePasswordRequest $request,
		UserInterface $userRepo,
	){
		if (!$userRepo->checkCurrentPassword($request->current_password)) {
			throw ValidationException::withMessages([
				'current_password' => 'Current password is incorrect.'
			]);
		}

		try {
			$userRepo->updatePassword($request->password);
		} catch (\Throwable $e) {
			throw ValidationException::withMessages([
				'error' => $e->getMessage()
			]);
		}
	}
}
