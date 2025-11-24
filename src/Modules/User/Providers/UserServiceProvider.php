<?php

namespace BilliftySDK\SharedResources\Modules\User\Providers;

use BilliftySDK\SharedResources\Modules\User\Auth\GoogleAuthService;
use BilliftySDK\SharedResources\Modules\User\Auth\PasswordAuthService;
use BilliftySDK\SharedResources\Modules\User\AuthTypes\GoogleAuthServiceInterface;
use BilliftySDK\SharedResources\Modules\User\AuthTypes\PasswordAuthServiceInterface;
use BilliftySDK\SharedResources\Modules\User\Repository\Contract\UserInterface;
use BilliftySDK\SharedResources\Modules\User\Repository\Eloquent\UserRepository;
use Illuminate\Support\ServiceProvider;

class UserServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(UserInterface::class, UserRepository::class);
        $this->app->bind(GoogleAuthServiceInterface::class, GoogleAuthService::class);
        $this->app->bind(PasswordAuthServiceInterface::class, PasswordAuthService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
