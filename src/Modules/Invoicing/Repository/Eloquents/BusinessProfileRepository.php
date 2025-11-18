<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Repository\Eloquents;

use BilliftySDK\SharedResources\Modules\Invoicing\Models\BusinessProfiles;
use BilliftySDK\SharedResources\Modules\Invoicing\Models\PaymentInformation;
use BilliftySDK\SharedResources\Modules\Invoicing\Repository\BaseRepository;
use BilliftySDK\SharedResources\Modules\Invoicing\Repository\Contracts\BusinessProfileContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BusinessProfileRepository extends BaseRepository implements BusinessProfileContract
{
	public function get()
	{
		return $this->getByUser()->get();
	}

	public function makeModel(): string
	{
		return BusinessProfiles::class;
	}

	public function getNewNameFromFile($file): string
	{
		// Generate a unique hashed filename
		$extension = $file->getClientOriginalExtension();     // png, jpg, etc.
		$hash = Str::random(40);
		// final filename: logo_{hash}.{ext}

		return "logo_{$hash}.{$extension}";
	}

	public function store(array $data, array $paymentInfoData)
	{
		$paymentInfo = $this->syncPaymentInfo($paymentInfoData);

		return $this->getByUser()->create([
			...$data,
			'user_id' => auth()->id(),
			'payment_information_id' => $paymentInfo->id
		]);
	}

	public function syncPaymentInfo(array $data): ?Model
	{
		if ($data['paymentInfo']) {
			return PaymentInformation::create($data['paymentInfo']);
		}

		return null;
	}
}