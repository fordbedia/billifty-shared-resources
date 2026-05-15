<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
{
	protected function relationArray(string $relation): mixed
	{
		if (!$this->resource->relationLoaded($relation)) {
			return null;
		}

		$value = $this->resource->getRelation($relation);

		if ($value instanceof \Illuminate\Support\Collection) {
			return $value->map(function ($item) {
				return method_exists($item, 'toArray') ? $item->toArray() : $item;
			})->values()->all();
		}

		return method_exists($value, 'toArray') ? $value->toArray() : $value;
	}

	protected function businessProfileArray(Request $request): ?array
	{
		if (!$this->resource->relationLoaded('businessProfile') || !$this->businessProfile) {
			return null;
		}

		$businessProfile = $this->businessProfile->toArray();

		if ($this->businessProfile->relationLoaded('paymentInformations')) {
			$businessProfile['paymentInformations'] = $this->businessProfile->paymentInformations
				->map(fn ($paymentInfo) => PaymentInformationResource::make($paymentInfo)->toArray($request))
				->values()
				->all();
		}

		return $businessProfile;
	}

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
			'user_id' => $this->user_id,
			'workspace_id' => $this->workspace_id,
           	'businessProfile' => $this->businessProfileArray($request),
			'client' => $this->relationArray('client'),
			'template' => $this->relationArray('template'),
			'colorScheme' => $this->relationArray('colorScheme'),
            'paymentInformations' => $this->resource->relationLoaded('businessProfile')
				&& $this->businessProfile?->relationLoaded('paymentInformations')
				? $this->businessProfile->paymentInformations
					->map(fn ($paymentInfo) => PaymentInformationResource::make($paymentInfo)->toArray($request))
					->values()
					->all()
				: [],
			'paymentLink' => $this->relationArray('paymentLink'),
			'items' => $this->relationArray('items'),
			'invoice_number' => $this->invoice_number,
			'reference' => $this->reference,
			'currency' => $this->relationArray('currency'),
			'issued_on' => $this->issued_on,
			'is_paid' => $this->is_paid,
			'is_issued' => $this->is_issued,
			'due_on' => $this->due_on,
			'paid_at' => $this->paid_at,
			'status' =>  $this->status,
			'template_slug' => $this->template_slug,
			'template_version' => $this->template_version,
			'theme_json' => $this->theme_json,
			'discount_mode' => $this->discount_mode,
			'subtotal_cents' => $this->subtotal_cents,
			'discount_cents' => $this->discount_cents,
			'discount_rate' => $this->discount_rate,
			'tax_cents' => $this->tax_cents,
			'shipping_cents' => $this->shipping_cents,
			'shipping_tax_rate' => $this->shipping_tax_rate,
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
