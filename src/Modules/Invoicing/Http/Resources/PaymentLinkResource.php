<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentLinkResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
			'link' => config('app.frontend_url') . "/pay/preview/invoice/token/{$this->token}",
			'public_token_revoked_at' => $this->public_token_revoked_at,
			'invoice_number' => $this->invoice->invoice_number,
			'invoice_status' => $this->invoice->status,
		];
    }
}
