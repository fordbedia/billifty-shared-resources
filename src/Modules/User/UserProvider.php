<?php

namespace BilliftySDK\SharedResources\Modules\User;

use BilliftySDK\SharedResources\Modules\User\Providers\UserServiceProvider;
use Illuminate\Support\ServiceProvider;

class UserProvider extends ServiceProvider
{
	protected array $providers = [
		UserServiceProvider::class,
	];

	public function register()
	{
		foreach ($this->providers as $provider) {
			$this->app->register($provider);
		}
	}
}