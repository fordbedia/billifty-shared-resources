<?php

namespace BilliftySDK\SharedResources\Modules\Billing\DTO;

use BilliftySDK\SharedResources\Modules\Billing\Application\Enums\PaymentProvider;

readonly class PaymentLinkResult
{
	public function __construct(
		public PaymentProvider $provider,
		public ?string         $url = null,
		public ?string         $externalReference = null,
		public ?array          $metadata = null,
	)
	{
	}
}