<?php

namespace BilliftySDK\SharedResources\Modules\Billing\DTO;

use BilliftySDK\SharedResources\Modules\Billing\Application\Enums\PaymentProvider;
use BilliftySDK\SharedResources\Modules\Billing\Application\Ports\InvoicePaymentLinkGateway;

readonly class CreateInvoicePaymentLinkData
{
	public function __construct(
		public string $token,
		public PaymentProvider           $provider,
		public ?int                      $businessProfileId = null,
		public ?string                   $successUrl = null,
		public ?string                   $cancelUrl = null,
	)
	{
	}
}