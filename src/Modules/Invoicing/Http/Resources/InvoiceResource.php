<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
{
	protected function relationArray(string $relation): mixed
	{
		if (!$this->resource->relationLoaded($relation)) {
			return null;
		}

		$value = $this->resource->getRelation($relation);

		if ($value instanceof \Illuminate\Support\Collection) {
			return $value->map(function ($item) {
				return method_exists($item, 'toArray') ? $item->toArray() : $item;
			})->values()->all();
		}

		return $value ? (method_exists($value, 'toArray') ? $value->toArray() : $value) : null;
	}

	protected function businessProfileArray(Request $request): ?array
	{
		if (!$this->resource->relationLoaded('businessProfile') || !$this->businessProfile) {
			return null;
		}

		$businessProfile = $this->businessProfile->toArray();

		if ($this->businessProfile->relationLoaded('paymentInformations')) {
			$paymentInformations = $this->businessProfile->paymentInformations
				->map(fn ($paymentInfo) => PaymentInformationResource::make($paymentInfo)->toArray($request))
				->values()
				->all();

			$businessProfile['paymentInformations'] = $paymentInformations;
			$businessProfile['payment_informations'] = $paymentInformations;
		}

		return $businessProfile;
	}

	protected function paymentInformationsArray(Request $request): array
	{
		if ($this->resource->usesSnapshot()) {
			return collect($this->resource->invoicePaymentInformationsData())
				->map(fn ($paymentInfo) => $this->normalizePaymentInformationArray($paymentInfo))
				->values()
				->all();
		}

		if (!$this->resource->relationLoaded('businessProfile') || !$this->businessProfile?->relationLoaded('paymentInformations')) {
			return [];
		}

		return $this->businessProfile->paymentInformations
			->map(fn ($paymentInfo) => PaymentInformationResource::make($paymentInfo)->toArray($request))
			->values()
			->all();
	}

	protected function normalizePaymentInformationArray(mixed $paymentInfo): array
	{
		$paymentInfo = is_array($paymentInfo)
			? $paymentInfo
			: (method_exists($paymentInfo, 'toArray') ? $paymentInfo->toArray() : (array) $paymentInfo);

		$paymentMethod = $paymentInfo['payment_method'] ?? null;
		$paymentMethod = $paymentMethod instanceof \BackedEnum
			? $paymentMethod->value
			: (string) $paymentMethod;
		$paymentMethodKey = str_replace([' ', '-'], '_', strtolower(trim($paymentMethod)));

		$paymentMethodLabels = [
			'bank_transfer' => 'Bank Transfer',
			'paypal' => 'PayPal',
			'cash_app' => 'Cash App',
			'stripe' => 'Stripe',
		];

		if (isset($paymentMethodLabels[$paymentMethodKey])) {
			$paymentInfo['payment_method'] = $paymentMethodLabels[$paymentMethodKey];
		}

		return $paymentInfo;
	}

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
		$businessProfile = $this->resource->usesSnapshot()
			? $this->resource->invoiceBusinessProfileData()
			: $this->businessProfileArray($request);
		$client = $this->resource->usesSnapshot()
			? $this->resource->invoiceClientData()
			: $this->relationArray('client');
		$colorScheme = $this->relationArray('colorScheme');
		$paymentInformations = $this->paymentInformationsArray($request);

		if ($this->resource->usesSnapshot() && $businessProfile !== null) {
			$businessProfile['paymentInformations'] = $paymentInformations;
			$businessProfile['payment_informations'] = $paymentInformations;
		}

		$paymentLink = $this->relationArray('paymentLink');
		$items = $this->relationArray('items');
		$reminderSchedule = $this->relationArray('reminderSchedule');
		$paymentReminders = $this->relationArray('paymentReminders');

		return array_merge($this->resource->attributesToArray(), [
			'business_profile' => $businessProfile,
			'businessProfile' => $businessProfile,
			'client' => $client,
			'template' => $this->relationArray('template'),
			'color_scheme' => $colorScheme,
			'colorScheme' => $colorScheme,
			'payment_informations' => $paymentInformations,
			'paymentInformations' => $paymentInformations,
			'payment_link' => $paymentLink,
			'paymentLink' => $paymentLink,
			'items' => $items,
			'invoice_items' => $items,
			'currency' => $this->relationArray('currency'),
			'reminder_schedule' => $reminderSchedule,
			'reminderSchedule' => $reminderSchedule,
			'payment_reminders' => $paymentReminders,
			'paymentReminders' => $paymentReminders,
		]);
    }
}
