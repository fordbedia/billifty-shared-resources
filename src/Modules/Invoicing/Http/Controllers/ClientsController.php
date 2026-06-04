<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Http\Controllers;

use Illuminate\Routing\Controller;
use BilliftySDK\SharedResources\Modules\Invoicing\Http\Requests\ClientRequest;
use BilliftySDK\SharedResources\Modules\Invoicing\Repository\Contracts\ClientsContract;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ClientsController extends Controller
{
	public function __construct()
	{
		$this->middleware(['plan.limit:max_clients'])->only(['store']);
	}

	/**
     * Display a listing of the resource.
     */
    public function index(
		Request $request,
		ClientsContract $clients
	) {
        return $clients->all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ClientRequest $request, ClientsContract $repo)
    {
		try {
			// Only form fields are accepted; workspace ownership is assigned by the repository.
			return $repo->save($request->validated());
		} catch(\Throwable $e) {
			$errors = ['errors' => [$e->getCode() => $e->getMessage()]];
			return response()->json($errors, Response::HTTP_INTERNAL_SERVER_ERROR);
		}
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id, ClientsContract $repo)
    {
		return $repo->findById($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
		ClientRequest $request,
		string $id,
		ClientsContract $repo
	) {
		// Only form fields are accepted; workspace ownership is assigned by the repository.
		return $repo->save($request->validated(), (int) $id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id, ClientsContract $repo)
    {
        return $repo->destroy($id);
    }

	public function paginate(Request $request, ClientsContract $repo)
	{
		$search = null;
		if ($request->search) {
			$search = $request->search;
		}
		return $repo->paginate(search: $search);
	}
}
