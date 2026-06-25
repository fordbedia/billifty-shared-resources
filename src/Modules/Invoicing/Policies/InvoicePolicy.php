<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Policies;

use BilliftySDK\SharedResources\Modules\Invoicing\Models\Invoices;
use BilliftySDK\SharedResources\Modules\User\Models\User;
use BilliftySDK\SharedResources\Modules\Billing\Support\PlanPermission;

class InvoicePolicy
{
	public function __construct(
		protected PlanPermission $planPermission
	)
	{
	}

	/**
	 * View any invoices.
	 * You might restrict this further later.
	 */
	public function viewAny(User $user): bool
	{
		return true;
	}

	/**
	 * View a specific invoice.
	 * Example: only invoices that belongs to this user.
	 */
	public function view(User $user, Invoices $invoice): bool
	{
		return (int) $invoice->workspace?->user_id === (int) $user->id;
	}

	/**
	 * Create a new invoice.
	 * Checks plan limit: `max_invoices_per_month`
	 */
	public function create(User $user): bool
	{
		$permission = $this->planPermission->forUser($user);

		return $permission->canWithinLimit(
			'max_invoices_per_month',
			$permission->currentUsage('max_invoices_per_month')
		);
	}

	/**
	 * Update invoice.
	 * Example: allow only if you own it.
	 */
	public function update(User $user, Invoices $invoice): bool
	{
		return (int) $invoice->workspace?->user_id === (int) $user->id;
	}

	/**
	 * Delete invoice.
	 */
	public function delete(User $user, Invoices $invoice): bool
	{
		return (int) $invoice->workspace?->user_id === (int) $user->id;
	}

	/**
	 * Custom ability: send invoice to client by email.
	 * You can back this with a capability like 'send_invoice_email'
	 * or reuse 'email_sending' / 'automated_reminders'.
	 */
	public function sendToClient(User $user, Invoices $invoice): bool
	{
		if ((int) $invoice->workspace?->user_id !== (int) $user->id) {
			return false;
		}

		// Example: require some capability; you can define this in plan_capabilities
		return $this->planPermission->forUser($user)->has('send_invoice_email');
	}

	/**
	 * Custom ability: enable online payments for this invoice.
	 */
	public function enableOnlinePayments(User $user, Invoices $invoice): bool
	{
		if ((int) $invoice->workspace?->user_id !== (int) $user->id) {
			return false;
		}

		return $this->planPermission->forUser($user)->has('online_payments');
	}
}
