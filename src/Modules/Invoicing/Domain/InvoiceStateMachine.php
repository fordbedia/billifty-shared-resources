<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Domain;

use BilliftySDK\SharedResources\Modules\Invoicing\Models\Invoices;
use DomainException;

final class InvoiceStateMachine
{
	private const IMMUTABLE_AFTER_ISSUE = [
		'invoice_number',
		'business_profile_id',
		'client_id',
		'currency_id',
		'issued_on',
		'due_on',
		'shipping_address',
		'subtotal_cents',
		'discount_mode',
		'discount_cents',
		'discount_rate',
		'tax_cents',
		'shipping_cents',
		'shipping_tax_rate',
		'shipping_tax_cents',
		'total_cents',
		'amount_due_cents',
	];

	private const ITEMS_IMMUTABLE_AFTER_ISSUE = [
		'description',
		'quantity',
		'unit_price_cents',
		'tax_rate',
		'line_discount_cents',
		'line_discount_rate',
		'tax_cents',
		'line_total_cents'
	];

	private const NUMERIC_ITEM_FIELDS = [
		'quantity',
		'unit_price_cents',
		'tax_rate',
		'line_discount_cents',
		'line_discount_rate',
		'tax_cents',
		'line_total_cents',
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
		if ($existing->status !== InvoiceStatus::ISSUED->value || $existing->status !== InvoiceStatus::PAID) return;

		foreach (self::IMMUTABLE_AFTER_ISSUE as $key) {
			if (array_key_exists($key, $incoming) && (string)$incoming[$key] !== (string)$existing->{$key}) {
				throw new DomainException("Field ".\Str::headline($key)." cannot be modified after issuing.");
			}
		}

		self::assertMutableItemFields($existing, $incoming);
	}

	public static function onIssue(Invoices $invoice): void
	{
		$invoice->status = InvoiceStatus::ISSUED->value;
		$invoice->issued_at = now();
	}

	private static function assertMutableItemFields(Invoices $existing, array $incoming): void
	{
		if (!array_key_exists('invoice_items', $incoming)) {
			return;
		}

		$incomingItems = collect($incoming['invoice_items'])->values();
		$existingItems = collect($existing->items ?? [])->values();
		$existingItemsById = $existingItems->keyBy(fn ($item) => (string) self::itemValue($item, 'id'));

		if ($incomingItems->count() !== $existingItems->count()) {
			throw new DomainException('Invoice items cannot be added or removed after issuing.');
		}

		foreach ($incomingItems as $index => $incomingItem) {
			$incomingItem = self::itemToArray($incomingItem);
			$existingItem = self::matchingExistingItem($incomingItem, $index, $existingItems, $existingItemsById);

			if (!$existingItem) {
				throw new DomainException('Invoice items cannot be added or removed after issuing.');
			}

			foreach (self::ITEMS_IMMUTABLE_AFTER_ISSUE as $key) {
				if (
					array_key_exists($key, $incomingItem)
					&& self::itemFieldChanged($key, $incomingItem[$key], self::itemValue($existingItem, $key))
				) {
					throw new DomainException("Field ".\Str::headline($key)." cannot be modified after issuing.");
				}
			}
		}
	}

	private static function matchingExistingItem(array $incomingItem, int $index, mixed $existingItems, mixed $existingItemsById): mixed
	{
		if (!empty($incomingItem['id'])) {
			return $existingItemsById->get((string) $incomingItem['id']);
		}

		return $existingItems->get($index);
	}

	private static function itemFieldChanged(string $key, mixed $incoming, mixed $existing): bool
	{
		if (in_array($key, self::NUMERIC_ITEM_FIELDS, true)) {
			return (float) ($incoming ?? 0) !== (float) ($existing ?? 0);
		}

		return (string) ($incoming ?? '') !== (string) ($existing ?? '');
	}

	private static function itemToArray(mixed $item): array
	{
		return is_array($item) ? $item : $item->toArray();
	}

	private static function itemValue(mixed $item, string $key): mixed
	{
		if (is_array($item)) {
			return $item[$key] ?? null;
		}

		return $item->{$key} ?? null;
	}
}
