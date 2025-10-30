<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
						'user_id' => $this->whenLoaded('user'),
           	'businessProfile' => $this->whenLoaded('businessProfile'),
						'client' => $this->whenLoaded('client'),
						'template' => $this->whenLoaded('template'),
						'colorScheme' => $this->whenLoaded('colorScheme'),
            'paymentInformation' => PaymentInformationResource::make(
								$this->whenLoaded('paymentInformation')
						),
						'items' => $this->whenLoaded('items'),
						'invoice_number' => $this->invoice_number,
						'reference' => $this->reference,
						'currency' => $this->currency,
						'issued_on' => $this->issued_on,
						'due_on' => $this->due_on,
						'paid_at' => $this->paid_at,
						'status' =>  $this->status,
						'template_slug' => $this->template_slug,
						'template_version' => $this->template_version,
						'theme_json' => $this->theme_json,
						'subtotal_cents' => $this->subtotal_cents,
						'discount_cents' => $this->discount_cents,
						'discount_rate' => $this->discount_rate,
						'tax_cents' => $this->tax_cents,
						'shipping_cents' => $this->shipping_cents,
						'total_cents' => $this->total_cents,
						'amount_due_cents' => $this->amount_due_cents,
						'notes' => $this->notes,
						'terms' => $this->terms,
						'pdf_url' => $this->pdf_url,
						'render_snapshot_html' => $this->render_snapshot_html,
						'meta' => $this->meta,
						'is_test' => $this->is_test
        ];
    }
}
