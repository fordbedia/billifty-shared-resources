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
			'discount_mode'  => ['nullable','in:none,amount,percent,per-line'],
			'discount_cents' => ['nullable','integer','min:0','required_if:discount_mode,amount'],
			'discount_rate'  => ['nullable','numeric','min:0','max:100','required_if:discount_mode,percent'],
            'shipping_cents'      => ['nullable','integer','min:0'],
            'shipping_tax_rate'   => ['nullable','numeric','min:0'],
            'invoice_items'       => ['required','array','min:1'],
			'invoice_items.*.line_discount_rate' => 'nullable|numeric|min:0|max:100',
//            'invoice_items.*.quantity'          => ['required','numeric','min:0'],
//            'invoice_items.*.unit_price_cents'  => ['required','integer','min:0'],
//            'invoice_items.*.tax_rate'          => ['nullable','numeric','min:0'],
//            'invoice_items.*.line_discount_cents' => ['nullable','integer','min:0'],
//            'invoice_items.*.line_discount_rate'  => ['nullable','numeric','min:0','max:1'],
        ];
    }
}
