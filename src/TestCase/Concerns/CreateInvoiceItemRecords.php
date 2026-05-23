<?php

namespace BilliftySDK\SharedResources\TestCase\Concerns;

use BilliftySDK\SharedResources\Modules\Invoicing\Models\InvoiceItems;

trait CreateInvoiceItemRecords
{
	/**
	 * @param int $invoiceId
	 * @param int $position
	 * @param string|null $name
	 * @param string|null $description
	 * @param float $quantity
	 * @param string|null $unit
	 * @param int $unitPriceCents
	 * @param int $lineDiscountCents
	 * @param float $lineDiscountRate
	 * @param float $taxRate
	 * @param int $taxCents
	 * @param int $lineTotalCents
	 * @return void
	 */
	public function create(
		?int $invoiceId = null,
		int $position = 1,
		?string $name = null,
		?string $description = null,
		float $quantity = 1.0000,
		?string $unit = null,
		int $unitPriceCents = 0,
		int $lineDiscountCents = 0,
		float $lineDiscountRate = 0.0000,
		float $taxRate = 0.0000,
		int $taxCents = 0,
		int $lineTotalCents = 0
	) {
		return InvoiceItems::create([
			'invoice_id' => $invoiceId ?? $this->invoiceId,
			'position' => $this->position ?? $position,
			'name' => $name ?? $this->name,
			'description' => $description ?? $this->description,
			'quantity' => $this->quantity ?? $quantity,
			'unit' => $unit ?? $this->unit,
			'unit_price_cents' => $this->unitPriceCents ?? $unitPriceCents,
			'line_discount_cents' => $this->lineDiscountCents ?? $lineDiscountCents,
			'line_discount_rate' => $this->lineDiscountRate ?? $lineDiscountRate,
			'tax_rate' => $this->taxRate ?? $taxRate,
			'tax_cents' => $this->taxCents ?? $taxCents,
			'line_total_cents' => $this->lineTotalCents ?? $lineTotalCents
		]);
	}
}