<?php

namespace BilliftySDK\SharedResources\Modules\Invoicing\Repository\Eloquents;

use BilliftySDK\SharedResources\Modules\Billing\Models\PaymentLink;
use BilliftySDK\SharedResources\Modules\Invoicing\Models\Invoices;
use BilliftySDK\SharedResources\Modules\Invoicing\Repository\Contracts\PaymentLinkRepository;
use BilliftySDK\SharedResources\Modules\Invoicing\Repository\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class EloquentPaymentLinkRepository extends BaseRepository implements PaymentLinkRepository
{
	public function makeModel(): string
	{
		return PaymentLink::class;
	}

	public function saveForToken(Invoices $invoices, array $payload): Model
	{
		$paymentLink = PaymentLink::whereInvoiceId($invoices->id)->first();
		if (!$paymentLink) {
			return PaymentLink::create([
				'invoice_id' => $invoices->id,
				'token' => $this->generateToken(),
				...$payload
			]);
		}
		return $paymentLink;
	}

	/**
	 * @return string
	 */
	public function generateToken(): string
	{
		return 'pay_' . Str::random('20') . '_' . Str::ulid();
	}

	public function findByToken(string $token): ?Model
	{
		return PaymentLink::whereToken($token)->first();
	}

	public function revoke(int $invoiceId): void
	{
		$link = $this->model->whereInvoiceId($invoiceId)->firstOrFail();
		$link->public_token_revoked_at = now();
		$link->save();
	}

	public function renew(int $invoiceId): void
	{
		$this->model->whereInvoiceId($invoiceId)->firstOrFail()
			->forceFill([
				'public_token_revoked_at' => null,
				'token' => $this->generateToken()
			])->save();
	}
}