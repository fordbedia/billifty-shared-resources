<?php

namespace BilliftySDK\SharedResources\Modules\Billing\Application\Resolvers;

use BilliftySDK\SharedResources\Modules\Billing\Application\Enums\PaymentProvider;
use BilliftySDK\SharedResources\Modules\Billing\Application\Ports\InvoicePaymentLinkGateway;
use RuntimeException;

class InvoicePaymentGatewayResolver
{
	public function __construct(
		protected iterable $gateways,
	)
	{
	}

	public function resolve(PaymentProvider $provider): InvoicePaymentLinkGateway
	{
		foreach ($this->gateways as $gateway) {
			if ($gateway->provider() === $provider) {
				return $gateway;
			}
		}

		throw new RuntimeException("Unsupported payment provider: {$provider->value}");
	}
}