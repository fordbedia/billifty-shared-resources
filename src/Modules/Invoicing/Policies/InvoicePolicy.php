<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Policies;

use App\Models\User; // or your actual User class
use BilliftySDK\SharedResources\Modules\Invoicing\Models\Invoices;
use BilliftySDK\SharedResources\Modules\User\Service\PlanCapabilityService;

class InvoicePolicy
{
	public function __construct(
		protected PlanCapabilityService $planCaps
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
		return $invoice->user_id === $user->id;
	}

	/**
	 * Create a new invoice.
	 * Checks plan limit: `max_invoices_per_month`
	 */
	public function create(User $user): bool
	{
		$invoicesThisMonth = $user->invoices()
			->whereBetween('created_at', [
				now()->startOfMonth(),
				now()->endOfMonth(),
			])->count();

		return $this->planCaps->canWithinLimit(
			$user,
			'max_invoices_per_month',
			$invoicesThisMonth
		);
	}

	/**
	 * Update invoice.
	 * Example: allow only if you own it.
	 */
	public function update(User $user, Invoices $invoice): bool
	{
		return $invoice->user_id === $user->id;
	}

	/**
	 * Delete invoice.
	 */
	public function delete(User $user, Invoices $invoice): bool
	{
		return $invoice->user_id === $user->id;
	}

	/**
	 * Custom ability: send invoice to client by email.
	 * You can back this with a capability like 'send_invoice_email'
	 * or reuse 'email_sending' / 'automated_reminders'.
	 */
	public function sendToClient(User $user, Invoices $invoice): bool
	{
		if ($invoice->user_id !== $user->id) {
			return false;
		}

		// Example: require some capability; you can define this in plan_capabilities
		return $this->planCaps->has($user, 'send_invoice_email');
	}

	/**
	 * Custom ability: enable online payments for this invoice.
	 */
	public function enableOnlinePayments(User $user, Invoices $invoice): bool
	{
		if ($invoice->user_id !== $user->id) {
			return false;
		}

		return $this->planCaps->has($user, 'online_payments');
	}
}
