<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Tests\Services;

use BilliftySDK\SharedResources\Modules\Invoicing\Models\Invoices;
use BilliftySDK\SharedResources\Modules\Invoicing\Services\InvoiceCalculator;
use BilliftySDK\SharedResources\TestCase\BaseTest;

class InvoiceCalculatorTest extends BaseTest
{
    /** @test */
    public function it_computes_amount_discount_shipping_tax_and_item_totals_for_array_items(): void
    {
        $invoice = new Invoices([
            'discount_mode' => 'amount',
            'discount_cents' => 500,
            'shipping_cents' => 1000,
            'shipping_tax_rate' => 8.25,
            'amount_due_cents' => 0,
        ]);

        $invoice->setRelation('items', [
            [
                'quantity' => 2,
                'unit_price_cents' => 1500,
                'tax_rate' => 10,
            ],
            [
                'quantity' => 1.5,
                'unit_price_cents' => 2000,
                'tax_rate' => 0,
            ],
        ]);

        $computed = (new InvoiceCalculator())->compute($invoice);
        $items = $computed->items->values();

        $this->assertSame(6000, $computed->subtotal_cents);
        $this->assertSame(300, $computed->tax_cents);
        $this->assertSame(83, $computed->shipping_tax_cents);
        $this->assertSame(6883, $computed->total_cents);
        $this->assertSame(6883, $computed->amount_due_cents);
        $this->assertSame([
            'label' => 'Discount',
            'amount_cents' => -500,
        ], $computed->getAttribute('display_discount_row'));

        $this->assertCount(2, $items);
        $this->assertSame(1, $items[0]['position']);
        $this->assertSame(300, $items[0]['tax_cents']);
        $this->assertSame(3300, $items[0]['line_total_cents']);
        $this->assertSame(2, $items[1]['position']);
        $this->assertSame(0, $items[1]['tax_cents']);
        $this->assertSame(3000, $items[1]['line_total_cents']);
    }

    /** @test */
    public function it_applies_per_line_percentage_discounts_and_ignores_invoice_level_discount_fields(): void
    {
        $invoice = new Invoices([
            'discount_mode' => 'per-line',
            'discount_cents' => 999,
            'discount_rate' => 50,
        ]);

        $invoice->setRelation('items', [
            [
                'quantity' => 2,
                'unit_price_cents' => 5000,
                'tax_rate' => 8.25,
                'line_discount_rate' => 10,
            ],
            [
                'quantity' => 1,
                'unit_price_cents' => 1000,
                'tax_rate' => 5,
                'line_discount_rate' => 0,
            ],
        ]);

        $computed = (new InvoiceCalculator())->compute($invoice);
        $items = $computed->items->values();

        $this->assertSame(10000, $computed->subtotal_cents);
        $this->assertSame(793, $computed->tax_cents);
        $this->assertSame(0, $computed->discount_cents);
        $this->assertSame(10793, $computed->total_cents);
        $this->assertNull($computed->getAttribute('display_discount_row'));

        $this->assertSame(9743, $items[0]['line_total_cents']);
        $this->assertSame(743, $items[0]['tax_cents']);
        $this->assertSame(1050, $items[1]['line_total_cents']);
        $this->assertSame(50, $items[1]['tax_cents']);
    }

    /** @test */
    public function it_normalizes_percent_discount_and_syncs_amount_due_when_present(): void
    {
        $invoice = new Invoices([
            'discount_mode' => 'percent',
            'discount_rate' => 12.5,
            'amount_due_cents' => '100',
        ]);

        $invoice->setRelation('items', [
            [
                'quantity' => 1,
                'unit_price_cents' => 1000,
                'tax_rate' => 0,
            ],
        ]);

        $computed = (new InvoiceCalculator())->compute($invoice);

        $this->assertSame(1000, $computed->subtotal_cents);
        $this->assertSame(125, $computed->discount_cents);
        $this->assertSame(0, $computed->tax_cents);
        $this->assertSame(875, $computed->total_cents);
        $this->assertSame(875, $computed->amount_due_cents);
        $this->assertSame([
            'label' => 'Discount',
            'amount_cents' => -125,
        ], $computed->getAttribute('display_discount_row'));
    }
}
