<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Policies;

use App\Models\User; // or your actual User class
use BilliftySDK\SharedResources\Modules\Invoicing\Models\Invoices;

class InvoicePolicy
{
    /**
     * Determine whether the user can view this invoice.
     */
    public function view(User $user, Invoices $invoice): bool
    {
        return (int) $invoice->user_id === (int) $user->id;
    }
}