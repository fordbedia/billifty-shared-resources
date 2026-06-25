<?php

namespace BilliftySDK\SharedResources\Modules\Billing\Exceptions;

use RuntimeException;

class PlanLimitExceededException extends RuntimeException
{
	public function __construct(
		public readonly string $limitKey,
		public readonly int $currentUsage,
		public readonly ?int $limit,
		string $message = 'You have reached the limit for this resource on your current plan.'
	) {
		parent::__construct($message);
	}
}
