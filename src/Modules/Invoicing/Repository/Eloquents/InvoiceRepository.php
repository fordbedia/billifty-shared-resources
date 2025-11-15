<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Repository\Eloquents;

use BilliftySDK\SharedResources\Modules\Invoicing\Helpers\InvoiceHelpers;
use BilliftySDK\SharedResources\Modules\Invoicing\Models\InvoiceItems;
use BilliftySDK\SharedResources\Modules\Invoicing\Models\Invoices;
use BilliftySDK\SharedResources\Modules\Invoicing\Repository\BaseRepository;
use BilliftySDK\SharedResources\Modules\Invoicing\Repository\Contracts\InvoiceContracts;
use RuntimeException;

class InvoiceRepository extends BaseRepository implements InvoiceContracts
{
	public function autoInvoiceNumber(): string
	{
		$lastInvoice = $this->getByUser()->latest()->pluck('invoice_number')->first();
		return InvoiceHelpers::incrementInvoiceNumber($lastInvoice);
	}

	public function findForUpdate(int $id): Invoices
	{
		$row = $this->model->whereKey($id)->lockForUpdate()->first();
		if (!$row) {
			throw new RuntimeException("Invoice {$id} could not be found.");
		}
		return $row->loadMissing('items');
	}

	public function syncItems(Invoices $invoice, iterable $items): void
	{
		$invoiceId = $invoice->getKey();

		$incoming = [];
		foreach ($items as $pos => $it) {
			$arr = is_array($it) ? $it : $it->toArray();
			$arr['position'] = $arr['position'] ?? ($pos + 1);
			$incoming[] = $arr;
		}

		// Fetch existing by position (or id)
		$existing = InvoiceItems::query()
			->where('invoice_id', $invoiceId)
			->get()
			->keyBy(fn ($r) => $r->id);

		$existingIds = [];

		foreach($incoming as $row) {
			$position = (int) ($row->position ?? 0);
			if (isset($row['id'])) {
				$existingIds[] = $row['id'];
			}

			$payload = [...$row, 'position' => $position];

			if (isset($row['id'])) {
				$existing[$row['id']]->fill($payload)->save();
			} else {
				$invoice->items()->create($payload);
			}
		}

		$toDelete = $existing->filter(fn ($r) => !in_array($r->id, $existingIds))->pluck('id');
		if ($toDelete->isNotEmpty()) {
			InvoiceItems::query()->whereIn('id', $toDelete->toArray())->delete();
		}

	}

	public function makeModel(): string
	{
		return Invoices::class;
	}

	public function hasDuplicateInvoice($invoiceNumber): bool
	{
		return $this->getByUser()
			->where('invoice_number', $invoiceNumber)
			->exists();
	}
}