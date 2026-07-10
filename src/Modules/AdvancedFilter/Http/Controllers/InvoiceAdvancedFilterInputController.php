<?php

namespace BilliftySDK\SharedResources\Modules\AdvancedFilter\Http\Controllers;

use BilliftySDK\SharedResources\Modules\AdvancedFilter\Application\Services\Metadata;
use BilliftySDK\SharedResources\Modules\AdvancedFilter\Http\Requests\AdvancedFilterSearchRequest;
use BilliftySDK\SharedResources\Modules\AdvancedFilter\Infrastructure\DTOs\AdvancedFilterInput;
use BilliftySDK\SharedResources\Modules\AdvancedFilter\Infrastructure\Engines\AdvancedFilterQueryProcessor;
use BilliftySDK\SharedResources\Modules\AdvancedFilter\Infrastructure\Engines\QueryEngine;
use BilliftySDK\SharedResources\Modules\AdvancedFilter\Infrastructure\Services\AdvancedFilterOptionService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
		$filterInput = new AdvancedFilterInput($request->advanced_filters);

		$query = $this->queryEngine->search($filterInput);
	}
}
