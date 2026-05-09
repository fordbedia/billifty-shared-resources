<?php

namespace BilliftySDK\SharedResources\Modules\Billing\Application\Ports;

use BilliftySDK\SharedResources\Modules\Billing\Application\Enums\PaymentProvider;
use BilliftySDK\SharedResources\Modules\Billing\DTO\CreateInvoicePaymentLinkData;
use BilliftySDK\SharedResources\Modules\Billing\DTO\PaymentLinkResult;

interface InvoicePaymentLinkGateway
{
	public function provider(): PaymentProvider;

	public function create(CreateInvoicePaymentLinkData $data): PaymentLinkResult;
}