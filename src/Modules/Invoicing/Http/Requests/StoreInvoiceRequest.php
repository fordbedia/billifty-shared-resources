<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInvoiceRequest extends FormRequest
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
            'business_profile_id' => ['required','integer','exists:business_profiles,id'],
            'client_id'           => ['required','integer','exists:clients,id'],
			'invoice_template_id' => ['required','integer','exists:invoice_templates,id'],
			'color_scheme_id'      => ['required','integer','exists:color_scheme,id'],
			'payment_information_id' => ['nullable', 'integer','exists:payment_informations,id'],
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
			'meta'					=> ['nullable','json'],
			'discount_mode'  => ['nullable','in:none,amount,percent,per-line'],
			'discount_cents' => ['nullable','integer','min:0','required_if:discount_mode,amount'],
			'discount_rate'  => ['nullable','numeric','min:0','max:100','required_if:discount_mode,percent'],
            'shipping_cents'      => ['nullable','integer','min:0'],
            'shipping_tax_rate'   => ['nullable','numeric','min:0'],
            'invoice_items'       => ['required','array','min:1'],
			'invoice_items.*.name' => 'nullable|string',
			'invoice_items.*.unit' => 'nullable|string',
			'invoice_items.*.line_discount_rate' => 'nullable|numeric|min:0|max:100',
            'invoice_items.*.quantity'          => ['required','numeric','min:0'],
            'invoice_items.*.unit_price_cents'  => ['required','integer','min:0'],
            'invoice_items.*.tax_rate'          => ['nullable','numeric','min:0'],
            'invoice_items.*.line_discount_cents' => ['nullable','integer','min:0'],
			'invoice_items.*.tax_cents'          => ['nullable','integer','min:0'],
			'invoice_items.*.line_total_cents'   => ['nullable','numeric','min:0'],
			'invoice_items.*.meta'        		=> 'nullable|json',
        ];
    }
}
