<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class PaymentLinkResource extends JsonResource
{
	protected function publicBusinessProfile(?array $businessProfile): ?array
	{
		return $businessProfile
			? Arr::except($businessProfile, [
				'tax_id',
				'license_no',
				'logo_disk',
				'workspace_id',
				'is_test',
				'deleted_at',
			])
			: null;
	}

	protected function publicPaymentInformations(array $paymentInformations): array
	{
		return collect($paymentInformations)
			->map(fn ($paymentInfo) => Arr::except($paymentInfo, [
				'stripe_account_id',
				'stripe_connected_at',
				'swift_code',
				'account_number',
				'account_name',
				'routing_number',
				'iban',
				'paypal_email',
				'paypal_payer_id',
				'paypal_merchant_id',
				'business_profile_id',
				'is_test',
				'deleted_at',
			]))
			->values()
			->all();
	}

	protected function paymentMethods(): array
	{
		return collect($this->resource->payment_methods)
			->map(fn ($paymentMethod) => $paymentMethod instanceof \BackedEnum ? $paymentMethod->value : $paymentMethod)
			->values()
			->all();
	}

	protected function userMethod(?array $user): array
	{
		return $user ? Arr::except($user, [
			'password',
			'deleted_at',
			'email',
			'email_verified_at',
			'created_at',
			'id',
			'plan_id',
			'plan_capabilities'
		]) : [];
	}

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
			'link' => config('app.frontend_url') . "/pay/preview/invoice/token/{$this->resource->token}",
			'public_token_revoked_at' => $this->resource->public_token_revoked_at,
			'invoice_number' => $this->resource->invoice->invoice_number,
			'invoice_status' => $this->resource->invoice->status,
			'id' => $this->resource->id,
			'user' => $this->userMethod($this->resource->user->toArray()),
			'invoice' => $this->whenLoaded('invoice', function() {
				$invoice = $this->resource->invoice;
				$paymentInformations = $this->publicPaymentInformations($invoice->invoicePaymentInformationsData());
				$businessProfile = $this->publicBusinessProfile($invoice->usesSnapshot()
					? $invoice->invoiceBusinessProfileData()
					: $invoice->businessProfile?->toArray());

				return [...Arr::except($invoice->toArray(), [
					'business_profile_snapshot',
					'payment_information_snapshot',
				]),
					'business_profile' => $businessProfile
						? [
							...$businessProfile,
							'paymentInformations' => $paymentInformations,
							'payment_informations' => $paymentInformations,
						]
						: null,
					'client' => $invoice->usesSnapshot()
						? $invoice->invoiceClientData()
						: $invoice->client(),

					'items' => $invoice->items->toArray()
				];
			}),
			'invoice_id' => $this->resource->invoice_id,
			'payment_methods' => $this->paymentMethods(),
			'paypal_capture_id' => $this->resource->paypal_capture_id,
			'paypal_order_id' => $this->resource->paypal_order_id,
			'public_token_expires_at' => $this->resource->public_token_expires_at,
			'public_token_revoked_at' => $this->resource->public_token_revoked_at,
			'token' => $this->resource->token,
			'updated_at' => $this->resource->updated_at,
			'created_at' => $this->resource->created_at,
			'deleted_at' => $this->resource->deleted_at,
			'expires_at' => $this->resource->expires_at,
		];
    }
}
