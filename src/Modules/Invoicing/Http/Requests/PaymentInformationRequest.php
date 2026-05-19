<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PaymentInformationRequest extends FormRequest
{
	protected const PAYMENT_METHODS = [
		'bank_transfer',
		'paypal',
		'stripe',
		'cash_app',
	];

	public function authorize(): bool
	{
		return true;
	}

	protected function prepareForValidation(): void
	{
		$paymentInfo = $this->input('paymentInfo');

		if (!is_array($paymentInfo)) {
			return;
		}

		$paymentInfo['payment_methods'] = $this->decodeArrayInput($paymentInfo['payment_methods'] ?? []);
		$paymentInfo['payment_information_ids'] = $this->decodeArrayInput($paymentInfo['payment_information_ids'] ?? []);
		$paymentInfo['payment_method'] = $this->normalizePaymentMethod($paymentInfo['payment_method'] ?? null);

		$paymentInfo['payment_methods'] = array_values(array_unique(array_filter(array_map(
			fn ($method) => $this->normalizePaymentMethod($method),
			$paymentInfo['payment_methods']
		))));

		if (empty($paymentInfo['payment_methods']) && $paymentInfo['payment_method']) {
			$paymentInfo['payment_methods'] = [$paymentInfo['payment_method']];
		}

		$this->merge([
			'paymentInfo' => $paymentInfo,
		]);
	}

	public function rules(): array
	{
		return [
			'paymentInfo.id' => ['nullable', 'integer'],

			'paymentInfo.payment_method' => ['nullable', 'string', Rule::in(self::PAYMENT_METHODS)],
			'paymentInfo.payment_methods' => ['nullable', 'array'],
			'paymentInfo.payment_methods.*' => ['string', Rule::in(self::PAYMENT_METHODS)],
			'paymentInfo.payment_information_ids' => ['nullable', 'array'],
			'paymentInfo.payment_information_ids.*' => ['nullable', 'integer'],

			// Bank Transfer
			'paymentInfo.bank_name' => [
				Rule::requiredIf(fn () => $this->isPaymentMethodSelected('bank_transfer')),
				'nullable',
				'string',
			],
			'paymentInfo.account_name' => [
				Rule::requiredIf(fn () => $this->isPaymentMethodSelected('bank_transfer')),
				'nullable',
				'string',
			],
			'paymentInfo.account_number' => [
				Rule::requiredIf(fn () => $this->isPaymentMethodSelected('bank_transfer')),
				'nullable',
				'string',
			],
			'paymentInfo.routing_number' => [
				Rule::requiredIf(fn () => $this->isPaymentMethodSelected('bank_transfer')),
				'nullable',
				'string',
			],

			// Stripe
			'paymentInfo.stripe_account_id' => [
				Rule::requiredIf(fn () => $this->isPaymentMethodSelected('stripe')),
				'nullable',
				'string',
			],

			// PayPal
			'paymentInfo.paypal_email' => [
				Rule::requiredIf(fn () => $this->isPaymentMethodSelected('paypal')),
				'nullable',
				'string',
			],

			'paymentInfo.paypal_merchant_id' => [
				Rule::requiredIf(fn () => $this->isPaymentMethodSelected('paypal')),
				'nullable',
				'string',
			],

			'paymentInfo.paypal_payer_id' => [
				Rule::requiredIf(fn () => $this->isPaymentMethodSelected('paypal')),
				'nullable',
				'string',
			],

			// Cash App
			'paymentInfo.cash_app' => [
				Rule::requiredIf(fn () => $this->isPaymentMethodSelected('cash_app')),
				'nullable',
				'string',
			],

			// Optional fields
			'paymentInfo.iban' => ['nullable', 'string'],
			'paymentInfo.swift_code' => ['nullable', 'string'],
			'paymentInfo.notes' => ['nullable', 'string'],
		];
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

	protected function normalizePaymentMethod(mixed $method): ?string
	{
		if (!$method) {
			return null;
		}

		return match (strtolower(trim((string) $method))) {
			'bank transfer' => 'bank_transfer',
			'paypal' => 'paypal',
			'stripe' => 'stripe',
			'cash app' => 'cash_app',
			default => strtolower(trim((string) $method)),
		};
	}

	protected function selectedPaymentMethods(): array
	{
		$methods = $this->input('paymentInfo.payment_methods', []);

		if (!is_array($methods)) {
			$methods = [];
		}

		$singleMethod = $this->normalizePaymentMethod($this->input('paymentInfo.payment_method'));

		if ($singleMethod) {
			$methods[] = $singleMethod;
		}

		return array_values(array_unique(array_filter(array_map(
			fn ($method) => $this->normalizePaymentMethod($method),
			$methods
		))));
	}

	protected function isPaymentMethodSelected(string $method): bool
	{
		return in_array($method, $this->selectedPaymentMethods(), true);
	}

	public function messages(): array
	{
		return [
			'paymentInfo.payment_method.in' => 'Please select a valid payment method.',
			'paymentInfo.payment_methods.*.in' => 'Please select a valid payment method.',

			// Bank Transfer
			'paymentInfo.bank_name.required' => 'Bank Name is required when Bank Transfer is selected.',
			'paymentInfo.account_name.required' => 'Account Name is required when Bank Transfer is selected.',
			'paymentInfo.account_number.required' => 'Account Number is required when Bank Transfer is selected.',
			'paymentInfo.routing_number.required' => 'Routing Number is required when Bank Transfer is selected.',

			// PayPal
			'paymentInfo.paypal_email.required' => 'PayPal Email is required when PayPal is selected.',
			'paymentInfopaypal_merchant_id.required' => 'PayPal Merchant ID is required when PayPal is selected.',
			'paymentInfopaypal_merchant_id.paypal_payer_id.required' => 'PayPal Payer ID is required when PayPal is selected.',

			// Cash App
			'paymentInfo.cash_app.required' => 'Cash App is required when Cash App is selected.',

			// Stripe
			'paymentInfo.stripe_account_id.required' =>
				'Stripe Account ID is required when Stripe is selected.',
		];
	}
}
