<?php

namespace BilliftySDK\SharedResources\TestCase\Concerns;

use BilliftySDK\SharedResources\Modules\Invoicing\Models\Invoices;

trait CreateInvoiceRecords
{
	public function create(
		?Workspace $workspace = null,
		?BusinessProfiles $businessProfile = null,
		?Clients $client = null,
		?InvoiceTemplates $template = null,
		?ColorScheme $colorScheme = null,
		?Currency $currency = null,
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
		return Invoices::create([
			'workspace_id' => $workspace?->id ?? $this->workspace->id,
			'business_profile_id' => $businessProfile?->id ?? $this->businessProfile->id,
			'client_id' => $client?->id ?? $this->client->id,
			'invoice_template_id' => $template?->id ?? $this->template->id,
			'color_scheme_id' => $colorScheme?->id ?? $this->colorScheme->id,
			'currency_id' => $currency?->id ?? $this->currency->id,
			'invoice_number' => $invoiceNumber ?? $this->invoiceNumber,
			'status' => $status ?? $this->status,
			'template_version' => $templateVersion ?? $this->templateVersion,
			'subtotal_cents' => $subTotalCents ?? $this->subTotalCents,
			'discount_mode' => $discountMode ?? $this->discountMode,
			'discount_cents' => $discountCents ?? $this->discountCents,
			'discount_rate' => $discountRate ?? $this->discountRate,
			'tax_cents' => $taxCents ?? $this->taxCents,
			'shipping_cents' => $shippingCents ?? $this->shippingCents,
			'shipping_tax_rate' => $shippingTaxRate ?? $this->shippingTaxRate,
			'shipping_tax_cents' => $shippingTaxCents ?? $this->shippingTaxCents,
			'total_cents' => $totalCents ?? $this->totalCents,
			'amount_due_cents' => $amountDueCents ?? $this->amountDueCents,
			'is_test' => 1,
			'notes' => $notes ?? $this->notes,
			'terms' => $terms ?? $this->terms,
		]);
	}
}