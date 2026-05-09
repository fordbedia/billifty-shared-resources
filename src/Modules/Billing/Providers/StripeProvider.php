<?php

namespace BilliftySDK\SharedResources\Modules\Billing\Providers;

use BilliftySDK\SharedResources\Modules\Billing\Application\Ports\StripeInvoicePaymentLink;
use BilliftySDK\SharedResources\Modules\Billing\Application\Resolvers\InvoicePaymentGatewayResolver;
use BilliftySDK\SharedResources\Modules\Billing\Contracts\PaymentGateway;
use BilliftySDK\SharedResources\Modules\Billing\Infrastructure\Payments\StripePaymentLink;
use BilliftySDK\SharedResources\Modules\Billing\Services\Billing\StripePaymentGateway;
use Illuminate\Support\ServiceProvider;
use Stripe\StripeClient;

class StripeProvider extends ServiceProvider
{
	protected bool $defer = true;

    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(StripeClient::class, fn() =>
			new StripeClient(config('services.stripe.secret'))
		);

		$this->app->bind(
			PaymentGateway::class,
			StripePaymentGateway::class
		);

		$this->app->singleton(InvoicePaymentGatewayResolver::class, function ($app) {
			return new InvoicePaymentGatewayResolver([
				$app->make(StripePaymentLink::class),
			]);
		});
	}

	/**
	 * Bootstrap services.
	 */
	public function boot(): void
	{
		//
	}

	public function provides(): array
	{
		return [
			StripeClient::class,
			PaymentGateway::class,
		];
	}
}
