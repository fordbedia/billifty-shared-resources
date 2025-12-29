<?php

namespace BilliftySDK\SharedResources\Modules\Billing\Domain;

enum FlowRedirectionEnum: string
{
	case KIND_CHECKOUT = 'checkout';

	case KIND_BILLING_PORTAL = 'billing_portal';

	case KIND_MANAGE_SUBSCRIPTION = 'manage_subscription';

	case KIND_SIGNIN = 'signin';
}
