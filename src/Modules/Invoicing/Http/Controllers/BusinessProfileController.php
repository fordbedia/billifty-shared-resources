<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Http\Controllers;

use App\Http\Controllers\Controller;
use BilliftySDK\SharedResources\Modules\Invoicing\Http\Requests\BusinessProfileRequest;
use BilliftySDK\SharedResources\Modules\Invoicing\Http\Requests\PaymentInformationRequest;
use BilliftySDK\SharedResources\Modules\Invoicing\Repository\Contracts\BusinessProfileContract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BusinessProfileController extends Controller
{
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
		BusinessProfileRequest $request,
		PaymentInformationRequest $paymentInformationRequest,
		BusinessProfileContract   $businessProfileRepo
	) {
		$data = $request->validated();
		$disk = config('filesystems.default', 'public');

		if ($request->hasFile('logo_path')) {
			$file = $request->file('logo_path');
			$year = now()->year;
			$filename = $businessProfileRepo->getNewNameFromFile($file);
			$path = $file->storeAs("logo_path/{$year}", $filename, $disk);

			$data['logo_path'] = $path;
			$data['logo_disk'] = $disk;
		}

		$paymentInfoData = $paymentInformationRequest->validated();

		$profile = $businessProfileRepo->createWithPaymentInfo($data, $paymentInfoData);

		return response()->json($profile, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id, BusinessProfileContract $businessProfile)
    {
        return $businessProfile->findById($id)->load(['paymentInformation']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
		string                    $id,
		BusinessProfileRequest    $businessProfileRequest,
		PaymentInformationRequest $paymentInformationRequest,
		BusinessProfileContract   $businessProfileRepo
	) {
		$data = $businessProfileRequest->validated();
		$paymentInfoData = $paymentInformationRequest->validated();

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
			$year = now()->year;
			$filename = $businessProfileRepo->getNewNameFromFile($file);
			$path = $file->storeAs("logo_path/{$year}", $filename, $disk);

			$data['logo_path'] = $path;
			$data['logo_disk'] = $disk;
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
}
