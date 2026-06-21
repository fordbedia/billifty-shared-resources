<?php

namespace App\Http\Controllers {
	if (! class_exists(Controller::class)) {
		class Controller {}
	}
}

namespace BilliftySDK\SharedResources\Modules\Billing\Tests\Http\Controllers {

	use BilliftySDK\SharedResources\Modules\Billing\Http\Controllers\InvoicePaymentController;
	use BilliftySDK\SharedResources\Modules\Billing\Models\PaymentLink;
	use BilliftySDK\SharedResources\Modules\Invoicing\Http\Resources\PaymentLinkResource;
	use BilliftySDK\SharedResources\Modules\Invoicing\Repository\Contracts\PaymentLinkRepository;
	use BilliftySDK\SharedResources\TestCase\BaseTest;
	use Illuminate\Http\Request;
	use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

	class InvoicePaymentControllerTest extends BaseTest
	{
		/** @test */
		public function payment_link_data_returns_the_loaded_payment_link_for_a_public_token(): void
		{
			$paymentLink = new PaymentLink(['token' => 'pay_test']);
			$repository = \Mockery::mock(PaymentLinkRepository::class);
			$repository->shouldReceive('findByToken')
				->once()
				->with('pay_test')
				->andReturn($paymentLink);

			$result = (new InvoicePaymentController)->paymentLinkData(
				Request::create('/invoice/pay_test', 'GET', ['token' => 'pay_test']),
				$repository
			);

			$this->assertInstanceOf(PaymentLinkResource::class, $result);
			$this->assertSame($paymentLink, $result->resource);
		}

		/** @test */
		public function payment_link_data_throws_not_found_when_token_cannot_be_resolved(): void
		{
			$repository = \Mockery::mock(PaymentLinkRepository::class);
			$repository->shouldReceive('findByToken')
				->once()
				->with('missing')
				->andReturn(null);

			$this->expectException(NotFoundHttpException::class);

			(new InvoicePaymentController)->paymentLinkData(
				Request::create('/invoice/missing', 'GET', ['token' => 'missing']),
				$repository
			);
		}
	}
}
