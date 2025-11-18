<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentInformationRequest extends FormRequest
{
	public function authorize(): bool
    {
        return true;
    }

	public function rules(): array
	{
		return [
			'paymentInfo.payment_method' => ['nullable', 'string'],
			'bank_name'        => ['required_if:payment_method,bank_transfer', 'string'],
			'account_name'     => ['required_if:payment_method,bank_transfer', 'string'],
			'account_number'   => ['required_if:payment_method,bank_transfer', 'string'],
			'routing_number'   => ['required_if:payment_method,bank_transfer', 'string'],
			'paymentInfo.iban' => ['nullable', 'string'],
			'paymentInfo.swift_code' => ['nullable', 'string'],
			'paymentInfo.paypal_email' => ['nullable', 'string'],
			'paymentInfo.cash_app' => ['nullable', 'string'],
			'paymentInfo.notes' => ['nullable', 'string'],
		];
	}
}