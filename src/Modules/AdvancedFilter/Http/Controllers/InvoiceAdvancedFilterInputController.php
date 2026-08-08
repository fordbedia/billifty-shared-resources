<?php

namespace BilliftySDK\SharedResources\Modules\AdvancedFilter\Http\Controllers;

use BilliftySDK\SharedResources\Modules\AdvancedFilter\Application\Services\Metadata;
use BilliftySDK\SharedResources\Modules\AdvancedFilter\Http\Requests\AdvancedFilterSearchRequest;
use BilliftySDK\SharedResources\Modules\AdvancedFilter\Infrastructure\DTOs\AdvancedFilterInput;
use BilliftySDK\SharedResources\Modules\AdvancedFilter\Infrastructure\Engines\AdvancedFilterQueryProcessor;
use BilliftySDK\SharedResources\Modules\AdvancedFilter\Infrastructure\Engines\QueryEngine;
use BilliftySDK\SharedResources\Modules\AdvancedFilter\Infrastructure\Services\AdvancedFilterOptionService;
use BilliftySDK\SharedResources\Modules\Invoicing\Http\Resources\InvoiceResource;
use BilliftySDK\SharedResources\Modules\Invoicing\Models\Invoices;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Illuminate\Support\Facades\DB;

class InvoiceAdvancedFilterInputController extends Controller
{
	public function __construct(
		public Metadata                              $metadata,
		private readonly AdvancedFilterOptionService $options,
		private readonly QueryEngine                 $queryEngine,
	){}

	/**
	 * Display a listing of the resource.
	 */
	public function index()
	{
		return $this->metadata->getMetadata();
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(Request $request)
	{
		//
	}

	/**
	 * Display the specified resource.
	 */
	public function show(string $id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(Request $request, string $id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(string $id)
	{
		//
	}

	public function options(Request $request)
	{
		return response()->json($this->options->getOptions(
			userId: $request->user()?->id,
			module: (string)$request->query('module', ''),
			valueSource: (string)$request->query('value_source', ''),
			search: (string)$request->query('search', ''),
			page: (int)$request->query('page', 1),
			perPage: (int)$request->query('per_page', 25),
		));
	}

	public function onSearch(AdvancedFilterSearchRequest $request)
	{
		return $this->searchInvoices(
			advancedFilters: $request->advanced_filters,
			page: (int)$request->query('page', 1),
			perPage: (int)$request->query('per_page', 15),
		);
	}

	public function searchFromRequest(Request $request): AnonymousResourceCollection
	{
		$advancedFilters = $request->query('advanced_filters', []);

		if (is_string($advancedFilters)) {
			$advancedFilters = json_decode($advancedFilters, true) ?: [];
		}

		return $this->searchInvoices(
			advancedFilters: $this->normalizeAdvancedFilters($advancedFilters),
			page: (int)$request->query('page', 1),
			perPage: (int)$request->query('per_page', 15),
		);
	}

	private function searchInvoices(array $advancedFilters, int $page = 1, int $perPage = 15): AnonymousResourceCollection
	{
		$page = max(1, $page);
		$perPage = min(100, max(1, $perPage));

		$filterInput = new AdvancedFilterInput($advancedFilters);
		$query = $this->queryEngine->search($filterInput);

		$rows = DB::select($query->sql, $query->bindings);
		$total = count($rows);
		$pageRows = array_slice($rows, ($page - 1) * $perPage, $perPage);
		$invoices = Invoices::hydrate(array_map(fn($row) => (array)$row, $pageRows))
			->load(Invoices::relationships());

		$paginator = new Paginator(
			items: $invoices,
			total: $total,
			perPage: $perPage,
			currentPage: $page,
			options: [
				'path' => request()->url(),
				'query' => request()->query(),
			],
		);

		$resource = InvoiceResource::collection($paginator);

		if (app()->environment(['local', 'dev'])) {
			$resource->additional([
				'debug' => [
					'query' => AdvancedFilterQueryProcessor::compileSqlQeury($query)
				],
			]);
		}

		return $resource;
	}

	private function normalizeAdvancedFilters(array $advancedFilters): array
	{
		foreach ($advancedFilters['groups'] ?? [] as $groupIndex => $group) {
			foreach ($group['conditions'] ?? [] as $conditionIndex => $condition) {
				if (($condition['operator'] ?? null) === '') {
					$advancedFilters['groups'][$groupIndex]['conditions'][$conditionIndex]['operator'] = null;
				}
			}
		}

		return $advancedFilters;
	}
}
