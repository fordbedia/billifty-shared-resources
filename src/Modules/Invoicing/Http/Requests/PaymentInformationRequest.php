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

            // Bank Transfer
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

            // Stripe
            'paymentInfo.stripe_payment_link' => [
                'exclude_unless:paymentInfo.payment_method,stripe',
                'required',
                'string',
            ],

            // PayPal
            'paymentInfo.paypal_email'       => [
                'exclude_unless:paymentInfo.payment_method,paypal',
                'required',
                'string',
            ],

            // Cash App
            'paymentInfo.cash_app'           => [
                'exclude_unless:paymentInfo.payment_method,cash_app',
                'required',
                'string',
            ],

            // Optional fields
            'paymentInfo.iban'               => ['nullable', 'string'],
            'paymentInfo.swift_code'         => ['nullable', 'string'],
            'paymentInfo.notes'              => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            // Bank Transfer
            'paymentInfo.bank_name.required'        => 'Bank Name is required when Bank Transfer is selected.',
            'paymentInfo.account_name.required'     => 'Account Name is required when Bank Transfer is selected.',
            'paymentInfo.account_number.required'   => 'Account Number is required when Bank Transfer is selected.',
            'paymentInfo.routing_number.required'   => 'Routing Number is required when Bank Transfer is selected.',

            // PayPal
            'paymentInfo.paypal_email.required'     => 'PayPal Email is required when PayPal is selected.',

            // Cash App
            'paymentInfo.cash_app.required'         => 'Cash App is required when Cash App is selected.',

            // Stripe
            'paymentInfo.stripe_payment_link.required' =>
                'Stripe Payment Link is required when Stripe is selected.',
        ];
    }
}