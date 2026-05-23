<?php

namespace BilliftySDK\SharedResources\TestCase\Concerns;

use BilliftySDK\SharedResources\Modules\User\Models\Plan;

trait CreatePlanRecords
{
	public function createFreePlan()
	{
		return Plan::updateOrCreate(['code' => 'free'], [
			'name' => 'Free',
			'description' => 'Try Billifty with limited clients and invoices.',
			'price_monthly' => 0.00,
			'price_yearly' => 0.00,
			'is_default' => 1,
			'sort_order' => 1
		]);
	}

	public function createProPlan()
	{
		return Plan::updateOrCreate(['code' => 'pro'], [
			'name' => 'Pro',
			'description' => 'For freelancers and small teams.',
			'price_monthly' => 4.99,
			'price_yearly' => 49.99,
			'is_default' => 1,
			'sort_order' => 1
		]);
	}

	public function createPremiumPlan()
	{
		return Plan::updateOrCreate(['code' => 'premium'], [
			'name' => 'Premium',
			'description' => 'TUnlimited invoicing and automation.',
			'price_monthly' => 9.99,
			'price_yearly' => 99.99,
			'is_default' => 1,
			'sort_order' => 1
		]);
	}
}
