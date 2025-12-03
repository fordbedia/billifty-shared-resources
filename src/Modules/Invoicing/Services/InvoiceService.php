<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Services;

use BilliftySDK\SharedResources\Modules\Invoicing\Domain\InvoiceAction;
use BilliftySDK\SharedResources\Modules\Invoicing\Domain\InvoiceStateMachine;
use BilliftySDK\SharedResources\Modules\Invoicing\Domain\InvoiceStatus;
use BilliftySDK\SharedResources\Modules\Invoicing\Models\Invoices;
use BilliftySDK\SharedResources\Modules\Invoicing\Repository\Contracts\InvoiceContracts;
use DomainException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InvoiceService
{
	public function __construct(
		protected InvoiceCalculator $calculator,
		protected InvoiceContracts $repo
	) {}

	public function upsert(
		array $data,
		InvoiceAction $action,
		?int $id = null
	): Invoices {
		DB::beginTransaction();

		$invoice = $id
			? $this->repo->findById($id)
			: new Invoices(Collection::make($data)->filter(fn ($inv, $key) =>
					!in_array($key, ['invoice_items', 'business_profile', 'template'])
				)->toArray());

		$fromStatus = InvoiceStatus::from($invoice->status ?? InvoiceStatus::DRAFT->value);
		if (!InvoiceStateMachine::canTransition($fromStatus, $action)) {
			DB::rollback();
			throw new DomainException("Cannot perform '{$action->value}' from status '{$fromStatus->value}'.");
		}

		// Check for ny duplicate invoice_number
		$duplicateInvoice = $this->repo->duplicateInvoice($data['invoice_number']);
		if ($duplicateInvoice && $duplicateInvoice->id !== $invoice->id) {
			DB::rollback();
			throw new DomainException("Invoice Number has duplicate. Please provide a unique invoice number.");
		}

		if ($invoice->exists) {
			InvoiceStateMachine::assertMutableFields($invoice, $data);
		}

		$payload = Collection::make($data)->except([
			'subtotal_cents','tax_cents','shipping_tax_cents','total_cents','amount_due_cents',
			'invoice_items','action',
		])->toArray();

		$invoice->fill($payload);

		if (!$invoice->exists && Auth::user()) {
			$invoice->user_id = Auth::id();
		}

		// Attach items transiently for compute
		$invoice->setRelation('items', $data['invoice_items'] ?? []);

		$this->calculator->compute($invoice);

		// keep a copy if you want to return it in the response
		$displayDiscountRow = $invoice->getAttribute('display_discount_row');

		// ----------------------------------------------------------------------------
		// Verify: cross-check frontend-sent totals for desync detection
		// ----------------------------------------------------------------------------
		if (
			isset($data['subtotal_cents'], $data['total_cents'])
			&& is_numeric($data['subtotal_cents'])
			&& is_numeric($data['total_cents'])
		) {
			$frontendSubtotal = (int) $data['subtotal_cents'];
			$frontendTotal    = (int) $data['total_cents'];
			$backendSubtotal  = (int) $invoice->subtotal_cents;
			$backendTotal     = (int) $invoice->total_cents;

			// Allow 1-cent rounding tolerance (optional)
			$withinTolerance = fn ($a, $b) => abs($a - $b) <= 1;

			if (!$withinTolerance($frontendSubtotal, $backendSubtotal)
				|| !$withinTolerance($frontendTotal, $backendTotal)) {
				DB::rollback();
				throw new \RuntimeException(sprintf(
					'Invoice total mismatch: frontend subtotal=%d, backend subtotal=%d; frontend total=%d, backend total=%d',
					$frontendSubtotal,
					$backendSubtotal,
					$frontendTotal,
					$backendTotal
				));
			}
		}

		// remove non-persistent attribute so Eloquent won't include it in UPDATE
		unset($invoice->display_discount_row);
		unset($invoice->action);

		$invoice->save();

		$this->repo->syncItems($invoice, $invoice->items);

		if ($action === InvoiceAction::Issue) {
			InvoiceStateMachine::onIssue($invoice);
			$invoice->save();
		}

		if ($displayDiscountRow !== null) {
			$invoice->setAttribute('display_discount_row', $displayDiscountRow);
		}

		DB::commit();
		return $invoice->refresh();
	}
}