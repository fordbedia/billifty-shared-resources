<?php

namespace App\Http\Controllers {
    if (! class_exists(Controller::class)) {
        class Controller {}
    }
}

namespace BilliftySDK\SharedResources\Modules\Billing\Tests\Http\Controllers {

    use BilliftySDK\SharedResources\Modules\Billing\Http\Controllers\PayPalPaymentController;
    use BilliftySDK\SharedResources\Modules\Billing\Infrastructure\Payments\PayPalGateway;
    use BilliftySDK\SharedResources\Modules\Billing\Models\PaymentLink;
    use BilliftySDK\SharedResources\Modules\Invoicing\Models\Invoices;
    use BilliftySDK\SharedResources\Modules\Invoicing\Services\Reminders\InvoicePaymentReminderService;
    use BilliftySDK\SharedResources\TestCase\BaseTest;
    use Illuminate\Http\JsonResponse;
    use Illuminate\Http\Request;

    class PayPalPaymentControllerTest extends BaseTest
    {
        /** @test */
        public function it_captures_a_paypal_order_when_the_order_approved_webhook_arrives(): void
        {
            $gateway = new FakePayPalGateway([
                'id' => 'ORDER-123',
                'status' => 'COMPLETED',
                'purchase_units' => [
                    [
                        'custom_id' => '456',
                        'payments' => [
                            'captures' => [
                                [
                                    'id' => 'CAPTURE-123',
                                    'status' => 'COMPLETED',
                                    'amount' => [
                                        'value' => '99.50',
                                        'currency_code' => 'USD',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ]);
            $controller = new TestablePayPalPaymentController;

            $response = $controller->handleWebhook(
                $this->webhookRequest([
                    'id' => 'WH-1',
                    'event_type' => 'CHECKOUT.ORDER.APPROVED',
                    'resource' => [
                        'id' => 'ORDER-123',
                        'purchase_units' => [
                            ['custom_id' => '456'],
                        ],
                    ],
                ]),
                $gateway
            );

            $this->assertSame('ORDER-123', $gateway->capturedOrderId);
            $this->assertSame(200, $response->getStatusCode());
            $this->assertSame('captured', $response->getData(true)['status']);
            $this->assertSame(456, $controller->completedInvoice?->id);
            $this->assertSame('CAPTURE-123', $controller->completedCaptureId);
        }

        /** @test */
        public function it_marks_a_paypal_capture_completed_webhook_without_capturing_again(): void
        {
            $gateway = new FakePayPalGateway([]);
            $controller = new TestablePayPalPaymentController;

            $response = $controller->handleWebhook(
                $this->webhookRequest([
                    'id' => 'WH-2',
                    'event_type' => 'PAYMENT.CAPTURE.COMPLETED',
                    'resource' => [
                        'id' => 'CAPTURE-456',
                        'status' => 'COMPLETED',
                        'custom_id' => '789',
                        'amount' => [
                            'value' => '50.00',
                            'currency_code' => 'USD',
                        ],
                        'supplementary_data' => [
                            'related_ids' => [
                                'order_id' => 'ORDER-456',
                            ],
                        ],
                    ],
                ]),
                $gateway
            );

            $this->assertNull($gateway->capturedOrderId);
            $this->assertSame(200, $response->getStatusCode());
            $this->assertSame('completed', $response->getData(true)['status']);
            $this->assertSame(789, $controller->completedInvoice?->id);
            $this->assertSame('CAPTURE-456', $controller->completedCaptureId);
        }

        /** @test */
        public function it_rejects_paypal_webhooks_with_invalid_signatures(): void
        {
            $gateway = new FakePayPalGateway([], false);
            $controller = new TestablePayPalPaymentController;

            $response = $controller->handleWebhook(
                $this->webhookRequest([
                    'id' => 'WH-BAD-SIG',
                    'event_type' => 'CHECKOUT.ORDER.APPROVED',
                    'resource' => [
                        'id' => 'ORDER-BAD-SIG',
                        'purchase_units' => [
                            ['custom_id' => '456'],
                        ],
                    ],
                ]),
                $gateway
            );

            $this->assertSame(400, $response->getStatusCode());
            $this->assertSame('invalid_signature', $response->getData(true)['status']);
            $this->assertNull($gateway->capturedOrderId);
        }

        /** @test */
        public function it_rejects_paypal_webhooks_without_an_event_id(): void
        {
            $gateway = new FakePayPalGateway([]);
            $controller = new TestablePayPalPaymentController;

            $response = $controller->handleWebhook(
                $this->webhookRequest([
                    'event_type' => 'CHECKOUT.ORDER.APPROVED',
                    'resource' => [
                        'id' => 'ORDER-MISSING-EVENT-ID',
                    ],
                ]),
                $gateway
            );

            $this->assertSame(400, $response->getStatusCode());
            $this->assertSame('missing_event_id', $response->getData(true)['status']);
            $this->assertNull($gateway->capturedOrderId);
        }

        /** @test */
        public function it_captures_a_paypal_order_when_paypal_redirects_back_to_the_backend_return_url(): void
        {
            $gateway = new FakePayPalGateway([
                'id' => 'ORDER-RETURN',
                'status' => 'COMPLETED',
                'purchase_units' => [
                    [
                        'custom_id' => '321',
                        'payments' => [
                            'captures' => [
                                [
                                    'id' => 'CAPTURE-RETURN',
                                    'status' => 'COMPLETED',
                                    'amount' => [
                                        'value' => '25.00',
                                        'currency_code' => 'USD',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ]);
            $controller = new TestablePayPalPaymentController;
            $paymentLink = new PaymentLink([
                'id' => 10,
                'token' => 'pay_test',
                'paypal_order_id' => 'ORDER-RETURN',
            ]);
            $invoice = new Invoices([
                'id' => 321,
                'status' => 'issued',
            ]);
            $invoice->setRelation('paymentLink', $paymentLink);
            $paymentLink->setRelation('invoice', $invoice);
            $controller->paymentLink = $paymentLink;

            $response = $controller->handleReturn(
                Request::create('/paypal/return/pay_test', 'GET', ['token' => 'ORDER-RETURN']),
                $gateway,
                'pay_test'
            );

            $this->assertSame('ORDER-RETURN', $gateway->capturedOrderId);
            $this->assertSame('https://frontend.test/app/invoices/pay_test/payment-success', $response->getTargetUrl());
            $this->assertSame(321, $controller->completedInvoice?->id);
            $this->assertSame('CAPTURE-RETURN', $controller->completedCaptureId);
        }

        private function webhookRequest(array $payload): Request
        {
            return Request::create('/api/v1/paypal/webhook', 'POST', $payload);
        }
    }

    class FakePayPalGateway extends PayPalGateway
    {
        public ?string $capturedOrderId = null;

        public function __construct(
            private readonly array $captureResponse,
            private readonly bool $shouldVerifyWebhookSignature = true,
        ) {}

        public function capturePayment(string $token): array
        {
            $this->capturedOrderId = $token;

            return $this->captureResponse;
        }

        public function verifyWebhookSignature(Request $request): bool
        {
            return $this->shouldVerifyWebhookSignature;
        }
    }

    class TestablePayPalPaymentController extends PayPalPaymentController
    {
        public ?Invoices $completedInvoice = null;

        public ?string $completedCaptureId = null;

        public ?PaymentLink $paymentLink = null;

        public function __construct()
        {
            parent::__construct(\Mockery::mock(InvoicePaymentReminderService::class));
        }

        protected function resolveInvoiceFromPayPalPayload(array $payload, ?array $captureResponse = null): ?Invoices
        {
            $invoiceId = $this->invoiceIdFromPayPalPayload($payload, $captureResponse);

            return $invoiceId ? new Invoices(['id' => $invoiceId]) : null;
        }

        protected function completeInvoicePayment(Invoices $invoice, array $captureData, array $webhookPayload = []): void
        {
            $this->completedInvoice = $invoice;
            $this->completedCaptureId = $this->captureIdFromCaptureResponse($captureData);
        }

        protected function storeWebhookEvent(array $payload, string $eventId, string $eventType): ?JsonResponse
        {
            return null;
        }

        protected function paymentLinkForToken(string $paymentToken): ?PaymentLink
        {
            return $this->paymentLink;
        }

        protected function frontendPaymentUrl(string $paymentToken, string $status): string
        {
            return "https://frontend.test/app/invoices/{$paymentToken}/{$status}";
        }
    }
}
