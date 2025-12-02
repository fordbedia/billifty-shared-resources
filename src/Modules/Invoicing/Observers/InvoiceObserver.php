<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Observers;


use BilliftySDK\SharedResources\Modules\Invoicing\Models\Invoices;
use BilliftySDK\SharedResources\Modules\Invoicing\Repository\Contracts\InvoiceContracts;

class InvoiceObserver
{
	public function __construct(
        protected InvoiceContracts $invoiceRepo,
    ) {}

    /**
     * Handle the Invoices "created" event.
     */
    public function created(Invoices $invoices): void
    {
        //
    }

    /**
     * Handle the Invoices "updated" event.
     */
    public function updated(Invoices $invoice): void
    {
        if ($invoice->pdf_path && $invoice->status !== 'issued') {
			 $this->invoiceRepo->issue($invoice);
		}
    }

    /**
     * Handle the Invoices "deleted" event.
     */
    public function deleted(Invoices $invoices): void
    {
        //
    }

    /**
     * Handle the Invoices "restored" event.
     */
    public function restored(Invoices $invoices): void
    {
        //
    }

    /**
     * Handle the Invoices "force deleted" event.
     */
    public function forceDeleted(Invoices $invoices): void
    {
        //
    }
}
