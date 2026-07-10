<?php

namespace BilliftySDK\SharedResources\Modules\AdvancedFilter\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdvancedFilterSearchRequest extends FormRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 */
	public function authorize(): bool
	{
		return true;
	}

	protected function prepareForValidation(): void
	{
		if (is_string($this->advanced_filters)) {
			$this->merge([
				'advanced_filters' => json_decode($this->advanced_filters, true),
			]);
		}
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
	 */
	public function rules(): array
	{
		return [
			'page' => ['nullable', 'integer', 'min:1'],
			'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
			'module' => ['required', 'string'],
			'advanced_filters' => ['required', 'array'],
			'advanced_filters.groups' => ['required', 'array'],
			'advanced_filters.groups.*.id' => ['required', 'integer'],
			'advanced_filters.groups.*.conditions' => ['required', 'array'],
			'advanced_filters.groups.*.conditions.*.id' => ['required', 'integer'],
			'advanced_filters.groups.*.conditions.*.field' => ['required', 'string'],
			'advanced_filters.groups.*.conditions.*.subField' => ['nullable', 'string'],
			'advanced_filters.groups.*.conditions.*.operator' => ['required', 'string'],
			'advanced_filters.groups.*.conditions.*.value' => ['nullable'],
		];
	}
}
