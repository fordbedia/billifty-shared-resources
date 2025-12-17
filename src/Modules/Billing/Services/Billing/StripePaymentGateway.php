<?php

namespace BilliftySDK\SharedResources\Modules\Billing\Services\Billing;

use BilliftySDK\SharedResources\Modules\Billing\Contracts\PaymentGateway;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Stripe\Customer;
use Stripe\StripeClient;

class StripePaymentGateway implements PaymentGateway
{
    public function __construct(protected StripeClient $client) {}

    public function ensureCustomer(AuthenticatableContract $user): string
    {
        if ($user->stripe_customer_id) {
            return $user->stripe_customer_id;
        }

        /** @var Customer $customer */
        $customer = $this->client->customers->create([
            'email'    => $user->email,
            'name'     => $user->name ?? null,
            'metadata' => [
                'billifty_user_id' => (string) $user->id,
            ],
        ]);

        $user->forceFill(['stripe_customer_id' => $customer->id])->save();

        return $customer->id;
    }

    public function resolvePriceId(string $planCode, string $billingCycle): string
    {
        $priceId = config("services.stripe.prices.{$planCode}.{$billingCycle}");

        if (! $priceId) {
            throw new \RuntimeException("Stripe price not configured for {$planCode}.{$billingCycle}");
        }

        return $priceId;
    }

    public function createCheckoutSessionUrl(
        string $customerId,
        string $priceId,
        string $successUrl,
        string $cancelUrl,
        array $metadata = []
    ): string {
		$glue = str_contains($successUrl, '?') ? '&' : '?';
		$successUrlWithSession = $successUrl . $glue . 'session_id={CHECKOUT_SESSION_ID}';
        $session = $this->client->checkout->sessions->create([
            'mode' => 'subscription',
            'customer' => $customerId,
            'line_items' => [
                [
                    'price' => $priceId,
                    'quantity' => 1,
                ],
            ],
            'success_url' => $successUrlWithSession,
            'cancel_url'  => $cancelUrl,

            // CRITICAL: metadata on session
            'metadata' => $metadata,

            // ALSO critical: metadata on subscription itself (for later subscription.updated events)
            'subscription_data' => [
                'metadata' => $metadata,
            ],

            // optional but helpful
            'allow_promotion_codes' => true,
        ]);

        return $session->url;
    }

    public function createBillingPortalSession(string $customerId, string $returnUrl): string
    {
        $session = $this->client->billingPortal->sessions->create([
            'customer'   => $customerId,
            'return_url' => $returnUrl,
        ]);

        return $session->url;
    }

}
