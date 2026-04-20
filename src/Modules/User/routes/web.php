<?php
use Illuminate\Http\Request;

Route::get('/email/verify/{id}/{hash}', function (Request $request, $id) {
    $userModel = config('auth.providers.users.model');
    $user = $userModel::findOrFail($id);

    if (! hash_equals(
        (string) $request->route('hash'),
        sha1($user->getEmailForVerification())
    )) {
        abort(403, 'Invalid verification link');
    }

    if (! $user->hasVerifiedEmail()) {
        $user->markEmailAsVerified();
    }

    // Redirect to your Next.js frontend
    return redirect(config('app.frontend_url') . '/app/account-settings');
})->middleware(['signed'])->name('verification.verify');
