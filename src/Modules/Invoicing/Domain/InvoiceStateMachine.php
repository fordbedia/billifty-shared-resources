<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Domain;

use BilliftySDK\SharedResources\Modules\Invoicing\Models\Invoices;
use DomainException;

final class InvoiceStateMachine
{
	private const IMMUTABLE_AFTER_ISSUE = [
		'invoice_number',
		'client_id',
		'currency_id',
	];

	public static function canTransition(InvoiceStatus $from, InvoiceAction $action): bool
	{
		return match ($from) {
			InvoiceStatus::DRAFT => in_array($action, [InvoiceAction::SaveDraft, InvoiceAction::SaveChanges, InvoiceAction::Issue], true),
			InvoiceStatus::ISSUED => in_array($action, [InvoiceAction::SaveChanges], true),
			InvoiceStatus::PAID, InvoiceStatus::VOID => false,
		};
	}

	public static function assertMutableFields(Invoices $existing, array $incoming): void
	{
		if ($existing->status !== InvoiceStatus::ISSUED->value) return;

		foreach (self::IMMUTABLE_AFTER_ISSUE as $key) {
			if (array_key_exists($key, $incoming) && (string)$incoming[$key] !== (string) $existing->{$key}) {
				throw new DomainException("Field '{$key}' cannot be modified after issuing.");
			}
		}
	}

	public static function onIssue(Invoices $invoice): void
	{
		$invoice->status = InvoiceStatus::ISSUED->value;
		$invoice->issued_at = now();
		$invoice->locked_fields = json_encode(self::IMMUTABLE_AFTER_ISSUE);
	}
}