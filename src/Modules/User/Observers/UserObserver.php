<?php

namespace BilliftySDK\SharedResources\Modules\User\Observers;

use BilliftySDK\SharedResources\Modules\User\Models\Onboarding;
use BilliftySDK\SharedResources\Modules\User\Models\User;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
		Onboarding::create([
			'user_id' => $user->id,
		]);
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        //
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
