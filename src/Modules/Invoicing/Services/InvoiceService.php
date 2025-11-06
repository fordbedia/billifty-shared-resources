<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Services;

use BilliftySDK\SharedResources\Modules\Invoicing\Models\Invoices;
use BilliftySDK\SharedResources\Modules\Invoicing\Repository\Contracts\InvoiceContracts;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class InvoiceService
{
	public function __construct(
		protected InvoiceCalculator $calculator,
		protected InvoiceContracts $invoiceContracts
	) {}

	public function create(array $data = [])
	{
		DB::beginTransaction();

		$newInvoice = new Invoices(Collection::make($data)->filter(fn ($inv, $key) =>
			!in_array($key, ['invoice_items', 'business_profile', 'template'])
		)->toArray());

		$newInvoice->setRelation('items', $data['invoice_items']);

		dump($newInvoice);

		$calculate = $this->calculator->compute($newInvoice);

		dd($calculate);
	}
}