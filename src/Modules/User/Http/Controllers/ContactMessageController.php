<?php

namespace BilliftySDK\SharedResources\Modules\User\Http\Controllers;

use App\Http\Controllers\Controller;
use BilliftySDK\SharedResources\Modules\User\Http\Requests\ContactMessageRequest;
use BilliftySDK\SharedResources\Modules\User\Mail\ContactMessageMail;
use BilliftySDK\SharedResources\Modules\User\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class ContactMessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ContactMessageRequest $request)
    {
        $payload = $request->validated();
        $user = Auth::guard('api')->user();

        if ($user) {
            $payload['user_id'] = $user->id;
            $payload['name'] = $payload['name'] ?? $user->name;
            $payload['email'] = $payload['email'] ?? $user->email;
        }

        $contactMessage = ContactMessage::create($payload);

        Mail::to($contactMessage->email)->send(
            new ContactMessageMail($contactMessage, 'user')
        );

        Mail::to($this->adminEmail())->send(
            new ContactMessageMail($contactMessage, 'admin')
        );

        return response()->json($contactMessage, 201);
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

    private function adminEmail(): string
    {
        return config('mail.admin.address')
            ?? config('services.admin.email')
            ?? config('mail.from.address', 'support@billifty.com');
    }
}
