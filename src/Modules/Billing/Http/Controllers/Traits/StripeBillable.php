<?php

namespace BilliftySDK\SharedResources\Modules\Billing\Http\Controllers\Traits;

use Carbon\Carbon;

trait StripeBillable
{
	private function resolveSubscriptionPeriodStart(object $subscription): ?Carbon
	{
		if (!empty($subscription->current_period_start)) {
			return Carbon::createFromTimestampUTC((int)$subscription->current_period_start);
		}

		$firstItem = $subscription->items->data[0] ?? null;

		if ($firstItem && !empty($firstItem->current_period_start)) {
			return Carbon::createFromTimestampUTC((int)$firstItem->current_period_start);
		}

		return null;
	}

	private function resolveSubscriptionPeriodEnd(object $subscription): ?Carbon
	{
		if (!empty($subscription->current_period_end)) {
			return Carbon::createFromTimestampUTC((int)$subscription->current_period_end);
		}

		$firstItem = $subscription->items->data[0] ?? null;

		if ($firstItem && !empty($firstItem->current_period_end)) {
			return Carbon::createFromTimestampUTC((int)$firstItem->current_period_end);
		}

		return null;
	}
}
