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

	public function findById(int $id)
	{
		return $this->getByUser()->findOrFail($id);
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

	public function createWithPaymentInfo(array $data, array $paymentInfoData): BusinessProfiles
	{
		$paymentInfo = $this->savePaymentInformation(null, $paymentInfoData);

		$data['user_id'] = auth()->id();
        $data['payment_information_id'] = $paymentInfo?->id;

		 /** @var BusinessProfiles $profile */
        $profile = $this->getByUser()->create($data);

        return $profile->fresh(['paymentInformation']);
	}

	/**
     * UPDATE
     */
    public function updateWithPaymentInfo(
        int $id,
        array $data,
        array $paymentInfoData
    ): BusinessProfiles {
        /** @var BusinessProfiles $profile */
        $profile = $this->findById($id);

        $paymentInfo = $this->savePaymentInformation(
            $profile->payment_information_id,
            $paymentInfoData
        );

        if ($paymentInfo) {
            $data['payment_information_id'] = $paymentInfo->id;
        }

        $profile->fill($data);
        $profile->save();

        return $profile->fresh(['paymentInformation']);
    }

	/**
     * “Upsert” payment info.
     * - If id exists → update
     * - Else → create
     */
    protected function savePaymentInformation(
        ?int $existingPaymentInformationId,
        array $paymentInfoData
    ): ?PaymentInformation {
        // If nothing was submitted, just bail out
        if (empty($paymentInfoData)) {
            return null;
        }

        // If you keep nesting under paymentInfo[...] in the request,
        // normalize here so the repo always deals with a flat array.
        if (isset($paymentInfoData['paymentInfo'])) {
            $paymentInfoData = $paymentInfoData['paymentInfo'];
        }

        if ($existingPaymentInformationId) {
            /** @var PaymentInformation $paymentInfo */
            $paymentInfo = PaymentInformation::findOrFail($existingPaymentInformationId);
            $paymentInfo->fill($paymentInfoData);
            $paymentInfo->save();

            return $paymentInfo;
        }

        return PaymentInformation::create($paymentInfoData);
    }

	public function paginate(
        $query = null,
        int $perPage = 15,
        array $columns = ['*'],
        string $pageName = 'page',
        int|null $page = null,
		$dateRange = null,
		$search = null,
    ) {
        // Add custom condition(s)
        $query = $this->getByUser()->whereNull('archived_at')->with(['paymentInformation']);

		if ($search) {
			$query->where(function ($query) use ($search) {
				$query->where('name', 'like', "%{$search}%")
					->orWhere('email', 'like', "%{$search}%");
			});
		}

        // You can chain more: ->where('type', 'admin')->orderBy('name')
        return parent::paginate($query, $perPage, $columns, $pageName, $page);
    }
}