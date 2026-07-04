<?php

namespace BilliftySDK\SharedResources\Modules\AdvancedFilter\Infrastructure\Services;

use BilliftySDK\SharedResources\Modules\AdvancedFilter\Application\Repository\Ports\AdvancedFilterOptionRepository;

class AdvancedFilterOptionService
{
	private const MODULE_INVOICES = 'invoices';
	private const SOURCE_INVOICE_NUMBERS = 'invoice_numbers';
	private const SOURCE_STATUSES = 'statuses';
	private const SOURCE_BUSINESS_PROFILES = 'business_profiles';
	private const SOURCE_BUSINESS_PROFILE = 'business_profile';
	private const SOURCE_CLIENTS = 'clients';
	private const SOURCE_CLIENT = 'client';

	public function __construct(
		private readonly AdvancedFilterOptionRepository $options,
	) {}

	public function getOptions(
		?int $userId,
		string $module,
		string $valueSource,
		string $search,
		int $page,
		int $perPage,
	): array {
		$page = $this->sanitizePage($page);
		$perPage = $this->sanitizePerPage($perPage);
		$search = trim($search);

		if ($module !== self::MODULE_INVOICES || !$userId) {
			return $this->emptyOptions($page, $perPage);
		}

		return match ($valueSource) {
			self::SOURCE_INVOICE_NUMBERS => $this->invoiceNumberOptions($userId, $search, $page, $perPage),
			self::SOURCE_STATUSES => $this->statusOptions($search, $page, $perPage),
			self::SOURCE_BUSINESS_PROFILES, self::SOURCE_BUSINESS_PROFILE => $this->businessProfileOptions($userId, $search, $page, $perPage),
			self::SOURCE_CLIENTS, self::SOURCE_CLIENT => $this->clientOptions($userId, $search, $page, $perPage),
			default => $this->emptyOptions($page, $perPage),
		};
	}

	private function invoiceNumberOptions(int $userId, string $search, int $page, int $perPage): array
	{
		$workspaceId = $this->options->defaultWorkspaceIdForUser($userId);

		if (!$workspaceId) {
			return $this->emptyOptions($page, $perPage);
		}

		$invoiceNumbers = $this->options->invoiceNumbers($workspaceId, $search, $page, $perPage);
		$options = $invoiceNumbers
			->take($perPage)
			->map(fn (string $invoiceNumber) => [
				'value' => $invoiceNumber,
				'label' => $invoiceNumber,
			])
			->values()
			->all();

		return $this->response($options, $page, $perPage, $invoiceNumbers->count() > $perPage);
	}

	private function statusOptions(string $search, int $page, int $perPage): array
	{
		$options = collect([
			['value' => 'draft', 'label' => 'Draft'],
			['value' => 'issued', 'label' => 'Issued'],
			['value' => 'paid', 'label' => 'Paid'],
			['value' => 'void', 'label' => 'Void'],
		]);

		if ($search !== '') {
			$options = $options->filter(function (array $option) use ($search): bool {
				$normalizedSearch = strtolower($search);

				return str_contains(strtolower($option['value']), $normalizedSearch)
					|| str_contains(strtolower($option['label']), $normalizedSearch);
			});
		}

		return $this->response($options->values()->all(), $page, $perPage, false);
	}

	private function businessProfileOptions(int $userId, string $search, int $page, int $perPage): array
	{
		$workspaceId = $this->options->defaultWorkspaceIdForUser($userId);

		if (!$workspaceId) {
			return $this->emptyOptions($page, $perPage);
		}

		$businessProfiles = $this->options->businessProfiles($workspaceId, $search, $page, $perPage);
		$options = $businessProfiles
			->take($perPage)
			->map(fn ($businessProfile) => [
				'value' => (string) $businessProfile->id,
				'label' => $this->entityLabel(
					$businessProfile->name,
					$businessProfile->legal_name,
					$businessProfile->email,
					'Business Profile #' . $businessProfile->id,
				),
			])
			->values()
			->all();

		return $this->response($options, $page, $perPage, $businessProfiles->count() > $perPage);
	}

	private function clientOptions(int $userId, string $search, int $page, int $perPage): array
	{
		$workspaceId = $this->options->defaultWorkspaceIdForUser($userId);

		if (!$workspaceId) {
			return $this->emptyOptions($page, $perPage);
		}

		$clients = $this->options->clients($workspaceId, $search, $page, $perPage);
		$options = $clients
			->take($perPage)
			->map(fn ($client) => [
				'value' => (string) $client->id,
				'label' => $this->entityLabel(
					$client->name,
					null,
					$client->email,
					'Client #' . $client->id,
				),
			])
			->values()
			->all();

		return $this->response($options, $page, $perPage, $clients->count() > $perPage);
	}

	private function emptyOptions(int $page, int $perPage): array
	{
		return $this->response([], $page, $perPage, false);
	}

	private function response(array $options, int $page, int $perPage, bool $hasMore): array
	{
		return [
			'data' => $options,
			'meta' => [
				'current_page' => $page,
				'per_page' => $perPage,
				'has_more' => $hasMore,
				'next_page' => $hasMore ? $page + 1 : null,
			],
		];
	}

	private function entityLabel(?string $name, ?string $secondaryName, ?string $email, string $fallback): string
	{
		$label = $name ?: $secondaryName ?: $email ?: $fallback;

		if ($email && $email !== $label) {
			return "{$label} ({$email})";
		}

		return $label;
	}

	private function sanitizePage(int $page): int
	{
		return max(1, $page);
	}

	private function sanitizePerPage(int $perPage): int
	{
		return min(50, max(1, $perPage));
	}
}
