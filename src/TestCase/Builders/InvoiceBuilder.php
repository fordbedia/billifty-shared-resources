<?php

namespace BilliftySDK\SharedResources\TestCase\Builders;

use BilliftySDK\SharedResources\Modules\Invoicing\Models\BusinessProfiles;
use BilliftySDK\SharedResources\Modules\Invoicing\Models\Clients;
use BilliftySDK\SharedResources\Modules\Invoicing\Models\ColorScheme;
use BilliftySDK\SharedResources\Modules\Invoicing\Models\Currency;
use BilliftySDK\SharedResources\Modules\Invoicing\Models\Invoices;
use BilliftySDK\SharedResources\Modules\Invoicing\Models\InvoiceTemplates;
use BilliftySDK\SharedResources\Modules\Invoicing\Models\InvoiceTemplateVersions;
use BilliftySDK\SharedResources\Modules\Invoicing\Models\Workspace;
use BilliftySDK\SharedResources\TestCase\Concerns\CreateInvoiceRecords;

class InvoiceBuilder
{
	use CreateInvoiceRecords;

	/**
	 * @var InvoiceItemsBuilder[]
	 */
	protected array $items = [];

	public function __construct(
		protected Workspace $workspace,
		protected BusinessProfiles $businessProfile,
		protected Clients $client,
		protected InvoiceTemplates $template,
		protected ColorScheme $colorScheme,
		protected Currency $currency,
		protected string $invoiceNumber = 'INV-00001',
		protected int $subTotalCents = 0,
		protected int $discountCents = 0,
		protected float $discountRate = 0.00,
		protected int $taxCents = 0,
		protected int $shippingCents = 0,
		protected float $shippingTaxRate = 0.00,
		protected int $shippingTaxCents = 0,
		protected int $totalCents = 0,
		protected int $amountDueCents = 0,
		protected string $discountMode = 'none',
		protected ?string $issuedOn = null,
		protected ?string $issuedAt = null,
		protected ?string $dueOn = null,
		protected ?string $paidAt = null,
		protected string $status = 'draft',
		protected ?string $templateSlug = null,
		protected int $templateVersion = 1,
		protected ?string $notes = null,
		protected ?string $terms = null,
	) {}

	public static function make(
		Workspace $workspace,
		BusinessProfiles $businessProfile,
		Clients $client,
		InvoiceTemplates $template,
		ColorScheme $colorScheme,
		Currency $currency,
		string $invoiceNumber = 'INV-00001',
		int $subTotalCents = 0,
		int $discountCents = 0,
		float $discountRate = 0.00,
		int $taxCents = 0,
		int $shippingCents = 0,
		float $shippingTaxRate = 0.00,
		int $shippingTaxCents = 0,
		int $totalCents = 0,
		int $amountDueCents = 0,
		string $discountMode = 'none',
		?string $issuedOn = null,
		?string $issuedAt = null,
		?string $dueOn = null,
		?string $paidAt = null,
		string $status = 'draft',
		?string $templateSlug = null,
		int $templateVersion = 1,
		?string $notes = null,
		?string $terms = null,
	) {
		return new self(
			$workspace,
			$businessProfile,
			$client,
			$template,
			$colorScheme,
			$currency,
			$invoiceNumber,
			$subTotalCents,
			$discountCents,
			$discountRate,
			$taxCents,
			$shippingCents,
			$shippingTaxRate,
			$shippingTaxCents,
			$totalCents,
			$amountDueCents,
			$discountMode,
			$issuedOn,
			$issuedAt,
			$dueOn,
			$paidAt,
			$status,
			$templateSlug,
			$templateVersion,
			$notes,
			$terms
		);
	}

	/**
	 * @param InvoiceItemsBuilder[] $items
	 */
	public function addItems(array $items): self
	{
		foreach ($items as $item) {
			if (!($item instanceof InvoiceItemsBuilder)) {
				abort(500, 'Invalid item type');
			}
		}

		$this->items = array_merge($this->items, $items);

		return $this;
	}

	public function create(...$arguments)
	{
		$invoice = $this->createInvoiceRecord(...$arguments);

		foreach ($this->items as $item) {
			$item->create(invoiceId: $invoice->id);
		}

		if ($this->items !== []) {
			$invoice->load('items');
		}

		return $invoice;
	}
}
