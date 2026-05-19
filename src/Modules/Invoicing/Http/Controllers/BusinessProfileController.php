<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Http\Controllers;


use BilliftySDK\SharedResources\Modules\Invoicing\Http\Requests\BusinessProfileRequest;
use BilliftySDK\SharedResources\Modules\Invoicing\Http\Requests\PaymentInformationRequest;
use BilliftySDK\SharedResources\Modules\Invoicing\Repository\Contracts\BusinessProfileContract;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class BusinessProfileController extends Controller
{
	public function __construct()
	{
		$this->middleware(['plan.limit:max_business_profiles'])->only(['store']);
	}

	/**
     * Display a listing of the resource.
     */
    public function index(
		Request $request,
		BusinessProfileContract $businessProfile
	) {
        return $businessProfile->get();
    }

	public function getAll(Request $request, BusinessProfileContract $businessProfile)
	{
		return $businessProfile->paginate(search: $request['search']);
	}

    /**
     * Store a newly created resource in storage.
     */
	public function store(
		BusinessProfileRequest    $request,
		PaymentInformationRequest $paymentInfoRequest,
		BusinessProfileContract   $businessProfileRepo
	) {
		$data = $request->validated();
		$disk = config('filesystems.default', 'public');

		if ($request->hasFile('logo_path')) {
			$file = $request->file('logo_path');

			// Let the repo + LogoImageProcessor handle resize & storage
			$logo = $businessProfileRepo->storeResizedLogo($file, $disk);

			$data['logo_path'] = $logo['logo_path'];
			$data['logo_disk'] = $logo['logo_disk'];
		}

		$paymentInfoData = $paymentInfoRequest->validated();

		$profile = $businessProfileRepo->createWithPaymentInfo($data, $paymentInfoData);

		return response()->json($profile, 201);
	}

    /**
     * Display the specified resource.
     */
    public function show(int $id, BusinessProfileContract $businessProfile)
    {
        return $businessProfile->findById($id)->load(['paymentInformations']);
    }

    /**
     * Update the specified resource in storage.
     */
	public function update(
		string                    $id,
		BusinessProfileRequest    $businessProfileRequest,
		PaymentInformationRequest $paymentInfoRequest,
		BusinessProfileContract   $businessProfileRepo
	) {
		$data = $businessProfileRequest->validated();
		$paymentInfoData = $paymentInfoRequest->validated();

		// Get current profile so we can handle old logo deletion
		$profile = $businessProfileRepo->findById((int) $id);

		$disk = config('filesystems.default', 'public');

		if ($businessProfileRequest->hasFile('logo_path')) {
			// delete old logo if it exists
			if ($profile->logo_path) {
				Storage::disk($profile->logo_disk ?? $disk)
					->delete($profile->logo_path);
			}

			$file = $businessProfileRequest->file('logo_path');

			// Same repo method for consistency
			$logo = $businessProfileRepo->storeResizedLogo($file, $disk);

			$data['logo_path'] = $logo['logo_path'];
			$data['logo_disk'] = $logo['logo_disk'];
		}

		$updated = $businessProfileRepo->updateWithPaymentInfo(
			(int) $id,
			$data,
			$paymentInfoData
		);

		return response()->json($updated);
	}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

	public function archive(int $id, BusinessProfileContract $repo)
	{
		return $repo->archive($id);
	}
}
