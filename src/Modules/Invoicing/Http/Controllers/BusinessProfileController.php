<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Http\Controllers;

use App\Http\Controllers\Controller;
use BilliftySDK\SharedResources\Modules\Invoicing\Http\Requests\BusinessProfileRequest;
use BilliftySDK\SharedResources\Modules\Invoicing\Http\Requests\PaymentInformationRequest;
use BilliftySDK\SharedResources\Modules\Invoicing\Repository\Contracts\BusinessProfileContract;
use Illuminate\Http\Request;

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

	public function getAll(BusinessProfileContract $businessProfile)
	{
		return $businessProfile->paginate();
	}

    /**
     * Store a newly created resource in storage.
     */
    public function store(
		BusinessProfileRequest $request,
		PaymentInformationRequest $paymentInformationRequest,
		BusinessProfileContract $businessProfile
	) {
		$data = $request->validated();

		$disk = config('filesystems.default') ?? 'local';

		if ($request->hasFile('logo_path')) {
			$year = now()->year;

			$file = $request->file('logo_path');

        	$filename = $businessProfile->getNewNameFromFile($file);

			$path = $file->storeAs("logo_path/{$year}", $filename, $disk);

			$data['logo_path'] = $path;
        	$data['logo_disk'] = $disk;
		}
		$paymentInfoData = $paymentInformationRequest->validated();

		$businessProfile = $businessProfile->store($data, $paymentInfoData);

		return response()->json($businessProfile, 201);
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
    public function update(Request $request, string $id, BusinessProfile $businessProfile)
    {
        $data = $request->validate([
			'name' => ['required', 'string', 'max:255'],
			// ...
			'logo' => ['nullable', 'image', 'max:2048'],
		]);

		$disk = 'public';

		if ($request->hasFile('logo')) {
			// optional: delete old logo
			if ($businessProfile->logo_path) {
				Storage::disk($businessProfile->logo_disk ?? 'public')
					->delete($businessProfile->logo_path);
			}

			$year = now()->year;
			$path = $request->file('logo')->store("logos/{$year}", $disk);

			$data['logo_path'] = $path;
			$data['logo_disk'] = $disk;
		}

		$businessProfile->update($data);

		return response()->json($businessProfile);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
