<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentInformationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
		{
			return [
				'id' => $this->id	,
				'user_id' => $this->whenLoaded('user'),
				'payment_method' => $this->payment_method?->label(),
				'bank_name' => $this->bank_name,
				'account_name' => $this->account_name,
				'account_number' => $this->account_number,
				'routing_number' => $this->routing_number,
				'iban' => $this->iban,
				'swift_code' => $this->swift_code,
				'paypal_email' => $this->paypal_email,
				'cash_app' => $this->cash_app,
				'notes' => $this->notes,
				'is_test' => $this->is_test
			];
		}
}
