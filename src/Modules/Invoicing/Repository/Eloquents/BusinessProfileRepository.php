<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Repository\Eloquents;

use BilliftySDK\SharedResources\Modules\Invoicing\Models\BusinessProfiles;
use BilliftySDK\SharedResources\Modules\Invoicing\Models\PaymentInformation;
use BilliftySDK\SharedResources\Modules\Invoicing\Repository\BaseRepository;
use BilliftySDK\SharedResources\Modules\Invoicing\Repository\Contracts\BusinessProfileContract;
use BilliftySDK\SharedResources\Modules\Invoicing\Support\LogoImageProcessor;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BusinessProfileRepository extends BaseRepository implements BusinessProfileContract
{
	protected const PAYMENT_METHODS = [
		'bank_transfer',
		'paypal',
		'stripe',
		'cash_app',
	];

	protected const PAYMENT_METHOD_FIELDS = [
		'bank_transfer' => [
			'bank_name',
			'account_name',
			'account_number',
			'routing_number',
			'iban',
			'swift_code',
		],
		'paypal' => [
			'paypal_email',
			'paypal_merchant_id',
			'paypal_payer_id',
		],
		'stripe' => [
			'stripe_account_id',
		],
		'cash_app' => [
			'cash_app',
		],
	];

	protected const MANAGED_PAYMENT_FIELDS = [
		'bank_name',
		'account_name',
		'account_number',
		'routing_number',
		'iban',
		'swift_code',
		'paypal_email',
		'paypal_merchant_id',
		'paypal_payer_id',
		'stripe_account_id',
		'cash_app',
	];

	public function get()
	{
		return $this->getModelByAuthUser()->with(['paymentInformations'])->get();
	}

	public function findById(int $id)
	{
		return $this->getModelByAuthUser()->with(['paymentInformations'])->findOrFail($id);
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

	public function storeResizedLogo(UploadedFile $file, ?string $disk = null): array
    {
        $disk = $disk ?? config('filesystems.default', 'public');

        return LogoImageProcessor::resizeAndStore(
            file: $file,
            disk: $disk,
            baseDirectory: 'logo_path'
        );
    }

	public function createWithPaymentInfo(array $data, array $paymentInfoData): BusinessProfiles
	{
		return DB::transaction(function () use ($data, $paymentInfoData) {
			$data['user_id'] = auth()->id();

			/** @var BusinessProfiles $profile */
			$profile = $this->getModelByAuthUser()->create($data);

			$this->savePaymentInformation($paymentInfoData, $profile->id);

			return $profile->fresh(['paymentInformations']);
		});
	}

	/**
     * UPDATE
     */
    public function updateWithPaymentInfo(
        int $id,
        array $data,
        array $paymentInfoData
    ): BusinessProfiles {
		return DB::transaction(function () use ($id, $data, $paymentInfoData) {
			/** @var BusinessProfiles $profile */
			$profile = $this->findById($id);

			$this->savePaymentInformation($paymentInfoData, $profile->id);

			$profile->fill($data);
			$profile->save();

			return $profile->fresh(['paymentInformations']);
		});
    }

	/**
     * Upsert payment information.
     * - Multiple selected methods create/update one row per method.
     */
    protected function savePaymentInformation(
        array $paymentInfoData,
		?int $businessProfileId = null
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

		$paymentInfoData = $this->normalizePaymentInformationData($paymentInfoData);
		$selectedMethods = $this->selectedPaymentMethods($paymentInfoData);

		if (empty($selectedMethods)) {
			$this->deleteUnselectedPaymentInformation(
				$businessProfileId,
				[],
				[]
			);

			return null;
		}

		$savedPaymentInformation = [];

		foreach ($selectedMethods as $method) {
			$paymentInfo = $this->findPaymentInformationForMethod(
				$method,
				$paymentInfoData['payment_information_ids'] ?? [],
				$businessProfileId
			);

			$payload = $this->paymentInfoPayload($method, $paymentInfoData, $businessProfileId);

			if ($paymentInfo) {
				$paymentInfo->fill($payload);
				$paymentInfo->save();
			} else {
				$paymentInfo = PaymentInformation::create($payload);
			}

			$savedPaymentInformation[$method] = $paymentInfo;
		}

		$this->deleteUnselectedPaymentInformation(
			$businessProfileId,
			$selectedMethods,
			array_map(fn (PaymentInformation $paymentInfo) => $paymentInfo->id, $savedPaymentInformation)
		);

		return $savedPaymentInformation[$selectedMethods[0]] ?? null;
    }

	protected function normalizePaymentInformationData(array $paymentInfoData): array
	{
		$paymentInfoData['payment_methods'] = $this->decodeArrayInput($paymentInfoData['payment_methods'] ?? []);
		$paymentInfoData['payment_information_ids'] = $this->decodeArrayInput($paymentInfoData['payment_information_ids'] ?? []);

		return $paymentInfoData;
	}

	protected function decodeArrayInput(mixed $value): array
	{
		if (is_array($value)) {
			return $value;
		}

		if (!is_string($value) || trim($value) === '') {
			return [];
		}

		$decoded = json_decode($value, true);

		return is_array($decoded) ? $decoded : [];
	}

	protected function selectedPaymentMethods(array $paymentInfoData): array
	{
		$methods = $paymentInfoData['payment_methods'] ?? [];

		if (!is_array($methods)) {
			$methods = [];
		}

		$singleMethod = $this->normalizePaymentMethod($paymentInfoData['payment_method'] ?? null);

		if ($singleMethod) {
			$methods[] = $singleMethod;
		}

		return array_values(array_unique(array_filter(array_map(
			fn ($method) => $this->normalizePaymentMethod($method),
			$methods
		), fn ($method) => in_array($method, self::PAYMENT_METHODS, true))));
	}

	protected function normalizePaymentMethod(mixed $method): ?string
	{
		if (!$method) {
			return null;
		}

		if ($method instanceof \BackedEnum) {
			return $method->value;
		}

		return match (strtolower(trim((string) $method))) {
			'bank transfer' => 'bank_transfer',
			'paypal' => 'paypal',
			'stripe' => 'stripe',
			'cash app' => 'cash_app',
			default => strtolower(trim((string) $method)),
		};
	}

	protected function findPaymentInformationForMethod(
		string $method,
		array $paymentInfoIds,
		?int $businessProfileId
	): ?PaymentInformation {
		$paymentInfoId = $paymentInfoIds[$method] ?? null;

		if ($paymentInfoId) {
			$query = PaymentInformation::whereKey((int) $paymentInfoId);

			if ($businessProfileId) {
				$query->where('business_profile_id', $businessProfileId);
			}

			$paymentInfo = $query->first();

			if ($paymentInfo) {
				return $paymentInfo;
			}
		}

		if ($businessProfileId) {
			$paymentInfo = PaymentInformation::where('business_profile_id', $businessProfileId)
				->where('payment_method', $method)
				->first();

			if ($paymentInfo) {
				return $paymentInfo;
			}
		}

		return null;
	}

	protected function paymentInfoPayload(
		string $method,
		array $paymentInfoData,
		?int $businessProfileId
	): array {
		$payload = array_fill_keys(self::MANAGED_PAYMENT_FIELDS, null);
		$payload['payment_method'] = $method;

		if ($businessProfileId) {
			$payload['business_profile_id'] = $businessProfileId;
		}

		foreach (self::PAYMENT_METHOD_FIELDS[$method] ?? [] as $field) {
			$payload[$field] = $paymentInfoData[$field] ?? null;
		}

		if (array_key_exists('notes', $paymentInfoData)) {
			$payload['notes'] = $paymentInfoData['notes'];
		}

		if (array_key_exists('is_test', $paymentInfoData) && $paymentInfoData['is_test'] !== '') {
			$payload['is_test'] = $paymentInfoData['is_test'];
		}

		return $payload;
	}

	protected function deleteUnselectedPaymentInformation(
		?int $businessProfileId,
		array $selectedMethods,
		array $savedPaymentInformationIds
	): void {
		if ($businessProfileId) {
			$query = PaymentInformation::where('business_profile_id', $businessProfileId);

			if (!empty($selectedMethods)) {
				$query->whereNotIn('payment_method', $selectedMethods);
			}

			if (!empty($savedPaymentInformationIds)) {
				$query->whereNotIn('id', $savedPaymentInformationIds);
			}

			$query->delete();
		}
	}

	public function archive(int $id)
	{
		return $this->getModelByAuthUser()->whereKey($id)->delete();
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
        $query = $this->getModelByAuthUser()->with(['paymentInformations']);

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
