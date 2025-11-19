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
            'paymentInfo.id'                 => ['nullable', 'integer'],

            'paymentInfo.payment_method'     => ['nullable', 'string'],

            // Only care about these IF payment_method === bank_transfer
            'paymentInfo.bank_name'          => [
                'exclude_unless:paymentInfo.payment_method,bank_transfer',
                'required',
                'string',
            ],
            'paymentInfo.account_name'       => [
                'exclude_unless:paymentInfo.payment_method,bank_transfer',
                'required',
                'string',
            ],
            'paymentInfo.account_number'     => [
                'exclude_unless:paymentInfo.payment_method,bank_transfer',
                'required',
                'string',
            ],
            'paymentInfo.routing_number'     => [
                'exclude_unless:paymentInfo.payment_method,bank_transfer',
                'required',
                'string',
            ],

            'paymentInfo.iban'               => ['nullable', 'string'],
            'paymentInfo.swift_code'         => ['nullable', 'string'],
            'paymentInfo.paypal_email'       => ['nullable', 'string'],
            'paymentInfo.cash_app'           => ['nullable', 'string'],
            'paymentInfo.notes'              => ['nullable', 'string'],
        ];
	}
}