<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInvoiceRequest extends FormRequest
{
	protected function prepareForValidation(): void
	{
		$data = $this->all();

		if (array_key_exists('meta', $data)) {
			$data['meta'] = $this->normalizeJsonValue($data['meta']);
		}

		if (isset($data['invoice_items']) && is_array($data['invoice_items'])) {
			$data['invoice_items'] = array_map(function ($item) {
				if (is_array($item) && array_key_exists('meta', $item)) {
					$item['meta'] = $this->normalizeJsonValue($item['meta']);
				}

				return $item;
			}, $data['invoice_items']);
		}

		$this->replace($data);
	}

	private function normalizeJsonValue(mixed $value): mixed
	{
		if ($value === '') {
			return null;
		}

		if (is_string($value)) {
			$decoded = json_decode($value, true);

			return json_last_error() === JSON_ERROR_NONE ? $decoded : $value;
		}

		return $value;
	}

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
            'business_profile_id' => ['required','integer','exists:business_profiles,id'],
            'client_id'           => ['required','integer','exists:clients,id'],
			'invoice_template_id' => ['required','integer','exists:invoice_templates,id'],
			'color_scheme_id'      => ['required','integer','exists:color_scheme,id'],
			'invoice_number'		=> ['required','string','max:100'],
			'reference'				=> ['nullable','string','max:100'],
			'currency_id'			=> ['required','integer','max:100'],
			'shipping_address'		=> ['nullable','string','max:100'],
			'issued_on'				=> ['required','date'],
			'due_on'				=> ['nullable','date'],
			'paid_at'				=> ['nullable','date'],
			'template_slug'			=> ['nullable','string','max:100'],
			'subtotal_cents' 		=> ['required','integer','min:0'],
			'tax_cents'				=> ['required','integer','min:0'],
			'shipping_tax_cents'	=> ['required','integer','min:0'],
			'total_cents'			=> ['required','integer','min:0'],
			'amount_due_cents'		=> ['required','integer','min:0'],
			'notes'					=> ['nullable','string'],
			'terms'					=> ['nullable','string'],
			'pdf_url'				=> ['nullable','string'],
			'render_snapshot_html'	=> ['nullable','string'],
			'meta'					=> ['nullable'],
			'discount_mode'  => ['nullable','in:none,amount,percent,per-line'],
			'discount_cents' => ['nullable','integer','min:0','required_if:discount_mode,amount'],
			'discount_rate'  => ['nullable','numeric','min:0','max:100','required_if:discount_mode,percent'],
            'shipping_cents'      => ['nullable','integer','min:0'],
            'shipping_tax_rate'   => ['nullable','numeric','min:0'],
            'invoice_items'       => ['required','array','min:1'],
			'invoice_items.*.id' => 'nullable|integer|exists:invoice_items,id',
			'invoice_items.*.position' => ['nullable','integer','min:1'],
			'invoice_items.*.name' => 'nullable|string',
			'invoice_items.*.description' => 'required|string',
			'invoice_items.*.invoice_id' => 'nullable|integer|exists:invoices,id',
			'invoice_items.*.unit' => 'nullable|string',
			'invoice_items.*.line_discount_rate' => 'nullable|numeric|min:0|max:100',
            'invoice_items.*.quantity'          => ['required','numeric','min:0'],
            'invoice_items.*.unit_price_cents'  => ['required','integer','min:0'],
            'invoice_items.*.tax_rate'          => ['nullable','numeric','min:0'],
            'invoice_items.*.line_discount_cents' => ['nullable','integer','min:0'],
			'invoice_items.*.tax_cents'          => ['nullable','integer','min:0'],
			'invoice_items.*.line_total_cents'   => ['nullable','numeric','min:0'],
			'invoice_items.*.meta'        		=> ['nullable'],
			'action' => 'required|string'
        ];
    }

	public function attributes(): array
	{
		return [
			'business_profile_id' => 'Business Profile',
            'client_id'           => 'Client',
			'invoice_number'		=> 'Invoice Number',
			// 'reference'				=> ['nullable','string','max:100'],
			'currency_id'			=> 'Currency',
			'shipping_address'		=> 'Shipping Address',
			'issued_on'				=> 'Issue Date',
			'due_on'				=> 'Due Date',
			// 'paid_at'				=> ['nullable','date'],
			// 'template_slug'			=> ['nullable','string','max:100'],
		];
	}

	public function messages(): array
	{
		return [
			'invoice_template_id' => 'The Invoice Template is required.',
			'color_scheme_id' => 'The Color Scheme is required.',
		];
	}
}
