<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Repository\Contracts;

use BilliftySDK\SharedResources\Modules\Invoicing\Models\Invoices;

interface InvoiceContracts
{
	public function findByKey(int $id): ?Invoices;

    public function queuePdfGeneration(Invoices $invoice): void;
}