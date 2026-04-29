<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Tests\Services;

use BilliftySDK\SharedResources\Modules\Invoicing\Domain\InvoiceAction;
use BilliftySDK\SharedResources\Modules\Invoicing\Models\Invoices;
use BilliftySDK\SharedResources\Modules\Invoicing\Repository\Contracts\InvoiceContracts;
use BilliftySDK\SharedResources\Modules\Invoicing\Services\InvoiceCalculator;
use BilliftySDK\SharedResources\Modules\Invoicing\Services\InvoiceService;
use BilliftySDK\SharedResources\TestCase\BaseTest;
use Illuminate\Support\Facades\DB;

class InvoiceServiceTest extends BaseTest
{
    /** @test */
    public function it_accepts_matching_frontend_totals_and_syncs_computed_items(): void
    {
        DB::shouldReceive('beginTransaction')->once();
        DB::shouldReceive('commit')->once();
        DB::shouldReceive('rollback')->never();

        $invoice = $this->makePersistedInvoice();
        $repo = new InMemoryInvoiceRepository($invoice);

        $service = new InvoiceService(new InvoiceCalculator(), $repo);

        $result = $service->upsert($this->basePayload([
            'subtotal_cents' => 2000,
            'total_cents' => 2000,
        ]), InvoiceAction::SaveChanges, 99);

        $this->assertSame(2000, $result->subtotal_cents);
        $this->assertSame(2000, $result->total_cents);
        $this->assertTrue($invoice->save_called);
        $this->assertCount(1, $repo->syncedItems);
        $this->assertSame(1, $repo->syncedItems[0]['position']);
        $this->assertSame(0, $repo->syncedItems[0]['tax_cents']);
        $this->assertSame(2000, $repo->syncedItems[0]['line_total_cents']);
    }

    /** @test */
    public function it_allows_a_one_cent_tolerance_between_frontend_and_backend_totals(): void
    {
        DB::shouldReceive('beginTransaction')->once();
        DB::shouldReceive('commit')->once();
        DB::shouldReceive('rollback')->never();

        $invoice = $this->makePersistedInvoice();
        $repo = new InMemoryInvoiceRepository($invoice);

        $service = new InvoiceService(new InvoiceCalculator(), $repo);

        $result = $service->upsert($this->basePayload([
            'subtotal_cents' => 2001,
            'total_cents' => 1999,
        ]), InvoiceAction::SaveChanges, 99);

        $this->assertSame(2000, $result->subtotal_cents);
        $this->assertSame(2000, $result->total_cents);
        $this->assertTrue($invoice->save_called);
    }

    /** @test */
    public function it_rolls_back_when_frontend_totals_do_not_match_backend_recompute(): void
    {
        DB::shouldReceive('beginTransaction')->once();
        DB::shouldReceive('commit')->never();
        DB::shouldReceive('rollback')->once();

        $invoice = $this->makePersistedInvoice();
        $repo = new InMemoryInvoiceRepository($invoice);

        $service = new InvoiceService(new InvoiceCalculator(), $repo);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Invoice total mismatch: frontend subtotal=1, backend subtotal=2000; frontend total=1, backend total=2000');

        try {
            $service->upsert($this->basePayload([
                'subtotal_cents' => 1,
                'total_cents' => 1,
            ]), InvoiceAction::SaveChanges, 99);
        } finally {
            $this->assertFalse($invoice->save_called);
            $this->assertSame([], $repo->syncedItems);
        }
    }

    private function makePersistedInvoice(): FakePersistedInvoice
    {
        $invoice = new FakePersistedInvoice([
            'id' => 99,
            'status' => 'draft',
            'invoice_number' => 'INV-99',
        ]);

        $invoice->exists = true;

        return $invoice;
    }

    private function basePayload(array $overrides = []): array
    {
        return array_merge([
            'invoice_number' => 'INV-99',
            'discount_mode' => 'none',
            'discount_cents' => 0,
            'discount_rate' => 0,
            'shipping_cents' => 0,
            'shipping_tax_rate' => 0,
            'amount_due_cents' => 0,
            'invoice_items' => [
                [
                    'description' => 'Line item',
                    'quantity' => 2,
                    'unit_price_cents' => 1000,
                    'tax_rate' => 0,
                ],
            ],
            'action' => 'save_changes',
        ], $overrides);
    }
}

class FakePersistedInvoice extends Invoices
{
    public bool $save_called = false;

    public function save(array $options = []): bool
    {
        $this->save_called = true;

        return true;
    }

    public function refresh(): static
    {
        return $this;
    }
}

class InMemoryInvoiceRepository implements InvoiceContracts
{
    /** @var array<int, array<string, mixed>> */
    public array $syncedItems = [];

    public function __construct(private readonly Invoices $invoice)
    {
    }

    public function findById(int $id): Invoices
    {
        return $this->invoice;
    }

    public function duplicateInvoice($invoiceNumber): ?Invoices
    {
        return null;
    }

    public function syncItems(Invoices $invoice, iterable $items): void
    {
        $this->syncedItems = collect($items)
            ->map(fn ($item) => is_array($item) ? $item : $item->toArray())
            ->values()
            ->all();
    }

    public function findByKey(int $id): ?Invoices
    {
        return $this->invoice;
    }

    public function queuePdfGeneration(Invoices $invoice): void
    {
    }
}
