<?php

namespace BilliftySDK\SharedResources\Modules\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'current_password' => ['required', 'string', 'min:5'],
			'new_password' => ['required', 'string', 'min:5'],
			'password' => ['required', 'string', 'min:5', 'confirmed:new_password'],
        ];
    }

	public function messages(): array
	{
		return [
			'current_password.required' => 'Current Password is required.',
			'new_password.required' => 'New Password is required.',
			'password.required' => 'Password Confirmation is required.',
			'password.confirmed' => 'Password Confirmation does not match.',
		];
	}
}
