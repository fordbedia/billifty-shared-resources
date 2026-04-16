<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Repository\Eloquents;

use BilliftySDK\SharedResources\Modules\Invoicing\Action\GenerateInvoicePdf;
use BilliftySDK\SharedResources\Modules\Invoicing\Domain\InvoiceStateMachine;
use BilliftySDK\SharedResources\Modules\Invoicing\Helpers\InvoiceHelpers;
use BilliftySDK\SharedResources\Modules\Invoicing\Jobs\GenerateInvoicePdfJob;
use BilliftySDK\SharedResources\Modules\Invoicing\Models\InvoiceItems;
use BilliftySDK\SharedResources\Modules\Invoicing\Models\Invoices;
use BilliftySDK\SharedResources\Modules\Invoicing\Models\Workspace;
use BilliftySDK\SharedResources\Modules\Invoicing\Repository\BaseRepository;
use BilliftySDK\SharedResources\Modules\Invoicing\Repository\Contracts\InvoiceContracts;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use RuntimeException;

class InvoiceRepository extends BaseRepository implements InvoiceContracts
{
	public function autoInvoiceNumber(): ?string
	{
		$workspaceId = $this->defaultWorkspaceIdForUser(Auth::id());
		if (!$workspaceId) {
			return null;
		}

		$lastInvoice = $this->model->newQuery()
			->where('workspace_id', $workspaceId)
			->latest()
			->pluck('invoice_number')
			->first();

		if ($lastInvoice) {
			return InvoiceHelpers::incrementInvoiceNumber($lastInvoice);
		}
		return null;
	}

	/*
	 * @dep name: findForUpdate(int $id): Invoices
	 */
	public function findById(int $id, ?int $authId = null): Invoices
	{
		$userId = $authId ?? Auth::user()->id ?? null;
		$row = $this->queryForUser($userId)
			->whereKey($id)
			->lockForUpdate()
			->first();

		if (!$row) {
			throw new RuntimeException("Invoice {$id} and user {$userId} could not be found.");
		}

		return $row->loadMissing(['items', 'workspace.user']);
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
			$position = (int) ($row['position'] ?? 0);
			$existingItem = isset($row['id']) ? $existing->get($row['id']) : null;

			if ($existingItem) {
				$existingIds[] = $row['id'];
			}

			$payload = [...$row, 'position' => $position];
			unset($payload['_sortable_id']);

			if ($existingItem) {
				$existingItem->fill($payload)->save();
			} else {
				unset($payload['id']);
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

	public function duplicateInvoice($invoiceNumber): ?Model
	{
		$workspaceId = $this->defaultWorkspaceIdForUser(Auth::id());
		if (!$workspaceId) {
			return null;
		}

		return $this->model->newQuery()
			->where('workspace_id', $workspaceId)
			->where('invoice_number', $invoiceNumber)
			->first();
	}

	public function deleteInvoice(int $id)
	{
		return $this->getModelByAuthUser()->whereKey($id)->delete();
	}

	public function paginate(
        $query = null,
        int $perPage = 15,
        array $columns = ['*'],
        string $pageName = 'page',
        int|null $page = null,
		$dateRange = null,
		$search = null,
    ) {
        // Add custom condition(s)
        $query = $this->getModelByAuthUser()->with(Invoices::relationships());

		if ($dateRange) {
			$query->whereBetween('issued_on', [$dateRange['start'], $dateRange['end']]);
		}

		if ($search) {
			$query->whereHas('client', function ($q1) use ($search) {
				$q1
					->where('name', 'like', "%{$search}%")
					->orWhere('email', 'like', "%{$search}%");
			});
		}

        // You can chain more: ->where('type', 'admin')->orderBy('name')
        return parent::paginate($query, $perPage, $columns, $pageName, $page);
    }

	public function findByKey(int $id): ?Invoices
    {
        /** @var Invoices|null $invoice */
        $query = Invoices::with(Invoices::relationships());

		if (Auth::check()) {
			$query->whereHas('workspace', function (Builder $workspaceQuery): void {
				$workspaceQuery->where('user_id', Auth::id());
			});
		}

        $invoice = $query->find($id);

        return $invoice;
    }

	public function getModelByAuthUser(): Builder
	{
		return $this->queryForUser(Auth::id());
	}

	protected function queryForUser(?int $userId): Builder
	{
		$query = $this->model->newQuery();

		if (!$userId) {
			return $query->whereRaw('1 = 0');
		}

		return $query->whereHas('workspace', function (Builder $workspaceQuery) use ($userId): void {
			$workspaceQuery->where('user_id', $userId);
		});
	}

	protected function defaultWorkspaceIdForUser(?int $userId): ?int
	{
		if (!$userId) {
			return null;
		}

		return Workspace::ensureDefaultForUser($userId)->getKey();
	}

	/**
	 * @param Invoices $invoice
	 * @return array
	 */
	public function generatePdf(Invoices $invoice): array
	{
		return (new GenerateInvoicePdf)($invoice);
	}

	/**
	 * @param Invoices $invoice
	 * @return void
	 */
    public function queuePdfGeneration(Invoices $invoice): void
    {
        GenerateInvoicePdfJob::dispatch($invoice->getKey());
    }

	public function issue(Invoices $invoice) {
		InvoiceStateMachine::onIssue($invoice);
		$invoice->save();
	}
}
