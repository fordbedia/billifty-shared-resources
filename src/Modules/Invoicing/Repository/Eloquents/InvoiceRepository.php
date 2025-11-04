<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Repository\Eloquents;

use BilliftySDK\SharedResources\Modules\Invoicing\Helpers\InvoiceHelpers;
use BilliftySDK\SharedResources\Modules\Invoicing\Models\Invoices;
use BilliftySDK\SharedResources\Modules\Invoicing\Repository\BaseRepository;
use BilliftySDK\SharedResources\Modules\Invoicing\Repository\Contracts\InvoiceContracts;

class InvoiceRepository extends BaseRepository implements InvoiceContracts
{
	public function autoInvoiceNumber(): string
	{
		$lastInvoice = $this->getByUser()->pluck('invoice_number')->first();
		return InvoiceHelpers::incrementInvoiceNumber($lastInvoice);
	}

	public function makeModel(): string
	{
		return Invoices::class;
	}
}