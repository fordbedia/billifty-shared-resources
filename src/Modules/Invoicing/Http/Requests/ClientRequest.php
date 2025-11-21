<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientRequest extends FormRequest
{
	public function authorize()
	{
		return true;
	}

	public function rules(): array
	{
		return [
			'name' => ['required', 'string'],
			'company' => ['nullable', 'string'],
			'email' => ['required', 'email'],
			'phone' => ['nullable', 'string', 'max:50'],
			'tax_id' => ['nullable', 'string'],
			'license_no' => ['nullable', 'string'],
			'address_line1' => ['nullable', 'string'],
			'address_line2' => ['nullable', 'string'],
			'city' => ['nullable', 'string'],
			'state' => ['nullable', 'string'],
			'postal_code' => ['nullable', 'string'],
			'country' => ['nullable', 'string'],
		];
	}
}