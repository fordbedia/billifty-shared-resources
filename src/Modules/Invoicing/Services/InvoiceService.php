<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Services;

use BilliftySDK\SharedResources\Modules\Invoicing\Domain\InvoiceAction;
use BilliftySDK\SharedResources\Modules\Invoicing\Domain\InvoiceStateMachine;
use BilliftySDK\SharedResources\Modules\Invoicing\Domain\InvoiceStatus;
use BilliftySDK\SharedResources\Modules\Invoicing\Models\BusinessProfiles;
use BilliftySDK\SharedResources\Modules\Invoicing\Models\Clients;
use BilliftySDK\SharedResources\Modules\Invoicing\Models\Invoices;
use BilliftySDK\SharedResources\Modules\Invoicing\Models\Workspace;
use BilliftySDK\SharedResources\Modules\Invoicing\Repository\Contracts\InvoiceContracts;
use DomainException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InvoiceService
{
	public function __construct(
		protected InvoiceCalculator $calculator,
		protected InvoiceContracts  $repo,
		protected InvoicePaymentLinkServices $paymentLinkServices
	)
	{
	}

	public function upsert(
		array         $data,
		InvoiceAction $action,
		?int          $id = null
	): Invoices
	{
		DB::beginTransaction();

		try {
			$invoice = $id
				? $this->repo->findById($id)
				: new Invoices(Collection::make($data)->filter(fn($inv, $key) => !in_array($key, ['invoice_items', 'business_profile', 'template'])
				)->toArray());

			$fromStatus = InvoiceStatus::from($invoice->status ?? InvoiceStatus::DRAFT->value);
			if (!InvoiceStateMachine::canTransition($fromStatus, $action)) {
				throw new DomainException("Cannot perform '{$action->value}' from status '{$fromStatus->value}'.");
			}

			// Duplicate invoice numbers are scoped to the user's default workspace.
			$duplicateInvoice = $this->repo->duplicateInvoice($data['invoice_number']);
			if ($duplicateInvoice && $duplicateInvoice->id !== $invoice->id) {
				throw new DomainException("Invoice Number has duplicate. Please provide a unique invoice number.");
			}

			if ($invoice->exists) {
				InvoiceStateMachine::assertMutableFields($invoice, $data);
			}

			$payload = Collection::make($data)->except([
				'subtotal_cents', 'tax_cents', 'shipping_tax_cents', 'total_cents', 'amount_due_cents',
				'invoice_items', 'action', 'workspace_id',
			])->toArray();

			$invoice->fill($payload);

			if (!$invoice->exists && Auth::user()) {
				$invoice->workspace_id = Workspace::ensureDefaultForUser(Auth::id())->getKey();
			}

			$this->assertInvoiceContactsBelongToWorkspace($invoice, $data);

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
				$frontendSubtotal = (int)$data['subtotal_cents'];
				$frontendTotal = (int)$data['total_cents'];
				$backendSubtotal = (int)$invoice->subtotal_cents;
				$backendTotal = (int)$invoice->total_cents;

				// Allow 1-cent rounding tolerance (optional)
				$withinTolerance = fn($a, $b) => abs($a - $b) <= 1;

				if (!$withinTolerance($frontendSubtotal, $backendSubtotal)
					|| !$withinTolerance($frontendTotal, $backendTotal)) {
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

			$invoice->forceFill([
				'pdf_path' => null,
				'pdf_status' => null,
				'pdf_generated_at' => null,
				'pdf_error' => null,
			]);

			if ($action === InvoiceAction::Issue) {
				InvoiceStateMachine::onIssue($invoice);
			}

			$invoice->save();

			$this->repo->syncItems($invoice, $invoice->items);

			// Save Payment Link
			$this->paymentLinkServices->createForInvoice($invoice, [
				'expires_at' => $this->paymentLinkServices->generateExpireAt(),
			]);

			if ($displayDiscountRow !== null) {
				$invoice->setAttribute('display_discount_row', $displayDiscountRow);
			}

			DB::commit();
			return $invoice->refresh();
		} catch (\Throwable $e) {
			// Keep the transaction boundary centralized so every validation failure rolls back.
			DB::rollBack();

			throw $e;
		}
	}

	protected function assertInvoiceContactsBelongToWorkspace(Invoices $invoice, array $data): void
	{
		$workspaceId = (int) $invoice->workspace_id;

		if (!$workspaceId) {
			throw new DomainException('Cannot save an invoice without a workspace.');
		}

		$businessProfileId = (int) ($data['business_profile_id'] ?? $invoice->business_profile_id);
		$clientId = (int) ($data['client_id'] ?? $invoice->client_id);

		// Row existence validation is not enough: these IDs must also be owned by the invoice workspace.
		$businessProfileBelongsToWorkspace = BusinessProfiles::query()
			->whereKey($businessProfileId)
			->where('workspace_id', $workspaceId)
			->exists();

		if (!$businessProfileBelongsToWorkspace) {
			throw new DomainException('Selected business profile does not belong to this workspace.');
		}

		$clientBelongsToWorkspace = Clients::query()
			->whereKey($clientId)
			->where('workspace_id', $workspaceId)
			->exists();

		if (!$clientBelongsToWorkspace) {
			throw new DomainException('Selected client does not belong to this workspace.');
		}
	}
}
