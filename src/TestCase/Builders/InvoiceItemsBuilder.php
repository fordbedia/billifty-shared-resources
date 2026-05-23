<?php

namespace BilliftySDK\SharedResources\TestCase\Builders;

use BilliftySDK\SharedResources\TestCase\Concerns\CreateInvoiceItemRecords;

class InvoiceItemsBuilder
{
	use CreateInvoiceItemRecords;

	public function __construct(
		protected int $invoiceId,
		protected int $position = 1,
		protected ?string $name = null,
		protected ?string $description = null,
		protected float $quantity = 1.0000,
		protected ?string $unit = null,
		protected int $unitPriceCents = 0,
		protected int $lineDiscountCents = 0,
		protected float $lineDiscountRate = 0.0000,
		protected float $taxRate = 0.0000,
		protected int $taxCents = 0,
		protected int $lineTotalCents = 0
	)
	{}

	public static function make(
		int $invoiceId,
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
		return new self(
			$invoicId,
			$position,
			$name,
			$description,
			$quantity,
			$unit,
			$unitPriceCents,
			$lineDiscountCents,
			$lineDiscountRate,
		);
	}
}