<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Services;

use BilliftySDK\SharedResources\Modules\Billing\Application\Enums\PaymentProvider;
use BilliftySDK\SharedResources\Modules\Billing\Models\PaymentLink;
use BilliftySDK\SharedResources\Modules\Invoicing\Models\Invoices;
use BilliftySDK\SharedResources\Modules\Invoicing\Repository\Contracts\PaymentLinkRepository;
use BilliftySDK\SharedResources\SDK\Application\Ports\Transactional;
use Carbon\Carbon;

class InvoicePaymentLinkServices
{
	public function __construct(
		protected PaymentLinkRepository $paymentLinkRepo,
		protected Transactional $db
	)
	{
	}

	public function createForInvoice(Invoices $invoices, array $payload): void
	{
		$this->db->run(function () use ($invoices, $payload) {
			$this->paymentLinkRepo->saveForToken($invoices, $payload);
		});
	}

	public function generateExpireAt()
	{
		return Carbon::now()->addWeek();
	}
}