<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BusinessProfileRequest extends FormRequest
{

	public function authorize(): bool
    {
        return true;
    }

	public function rules(): array
	{
		return [
			'id' => ['nullable', 'integer'],
			'payment_information_id' => ['nullable', 'integer'],
			'user_id' => ['nullable', 'integer'],
			'name' => ['required', 'string', 'max:255'],
			'legal_name' => ['required', 'string', 'max:255'],
			'email' => ['required', 'string', 'max:255'],
			'phone' => ['nullable', 'string'],
			'website' => ['nullable', 'string'],
			'tax_id' => ['nullable', 'string'],
			'license_no' => ['nullable', 'string'],
			'address_line1' => ['nullable', 'string'],
			'address_line2' => ['nullable', 'string'],
			'city' => ['nullable', 'string'],
			'state' => ['nullable', 'string'],
			'postal_code' => ['nullable', 'string'],
			'country' => ['nullable', 'string'],
			'logo_disk' => ['nullable', 'string'],
			'logo_path' => ['nullable'],
			'branding_json' => ['nullable', 'string'],
		];
	}

	public function attributes(): array
	{
		return [
			'name' => 'Name',
			'legal_name' => 'Legal Name',
			'email' => 'Email',
		];
	}
}