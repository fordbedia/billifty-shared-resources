<?php

namespace BilliftySDK\SharedResources\Modules\Billing\Repositories\Eloquents;

use BilliftySDK\SharedResources\Modules\Billing\Models\UserSubscription;
use BilliftySDK\SharedResources\Modules\Billing\Repositories\BaseRepository;
use BilliftySDK\SharedResources\Modules\Billing\Repositories\Interfaces\UserSubscriptionInterface;

class UserSubscriptionRepository extends BaseRepository implements UserSubscriptionInterface
{
	public function makeModel(): string
	{
		return UserSubscription::class;
	}

	public function hasSubscribed()
	{
		return $this->getModelByAuthUser()->exists();
	}

	public function subscription()
	{
		return $this->getModelByAuthUser()->first();
	}

	public function upsert(array $data = [])
	{
		$subscription = $this->getModelByAuthUser()->firstOrNew();
		$subscription = $subscription->fill($data);


		return $subscription->save();
	}
}