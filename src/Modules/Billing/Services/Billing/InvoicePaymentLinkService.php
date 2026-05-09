<?php

namespace BilliftySDK\SharedResources\Modules\Billing\Services\Billing;

use BilliftySDK\SharedResources\Modules\Billing\Application\Enums\PaymentProvider;
use BilliftySDK\SharedResources\Modules\Billing\Application\Resolvers\InvoicePaymentGatewayResolver;
use BilliftySDK\SharedResources\Modules\Billing\DTO\CreateInvoicePaymentLinkData;
use BilliftySDK\SharedResources\Modules\Billing\DTO\PaymentLinkResult;

class InvoicePaymentLinkService
{
	public function __construct(
		protected InvoicePaymentGatewayResolver $resolver,
	)
	{
	}

	public function createForInvoice(
		string $token,
		PaymentProvider           $provider,
		?int                      $businessProfileId = null,
		?string                   $successUrl = null,
		?string                   $cancelUrl = null,
	): PaymentLinkResult
	{
		$gateway = $this->resolver->resolve($provider);

		return $gateway->create(
			new CreateInvoicePaymentLinkData(
				token: $token,
				provider: $provider,
				businessProfileId: $businessProfileId,
				successUrl: $successUrl,
				cancelUrl: $cancelUrl,
			)
		);
	}
}