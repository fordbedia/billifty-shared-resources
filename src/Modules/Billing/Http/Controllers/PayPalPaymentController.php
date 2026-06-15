<?php

namespace BilliftySDK\SharedResources\Modules\Billing\Http\Controllers;

use App\Http\Controllers\Controller;
use BilliftySDK\SharedResources\Modules\Billing\Application\Enums\PaymentProvider;
use BilliftySDK\SharedResources\Modules\Billing\Infrastructure\Payments\PayPalGateway;
use BilliftySDK\SharedResources\Modules\Billing\Mail\PaymentSuccessNotificationForBusinessProfileMail;
use BilliftySDK\SharedResources\Modules\Billing\Mail\PaymentSuccessNotificationForClientMail;
use BilliftySDK\SharedResources\Modules\Billing\Models\PaymentLink;
use BilliftySDK\SharedResources\Modules\Billing\Models\PaymentRecord;
use BilliftySDK\SharedResources\Modules\Billing\Models\PayPalWebhookEvent;
use BilliftySDK\SharedResources\Modules\Invoicing\Models\Invoices;
use BilliftySDK\SharedResources\Modules\Invoicing\Services\Reminders\InvoicePaymentReminderService;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PayPalPaymentController extends Controller
{
	public function __construct(
		protected InvoicePaymentReminderService $paymentReminderService,
	) {}

    public function create(
        Invoice $invoice,
        PayPalGateway $paypal
    ) {
        $paymentUrl = $paypal->createPaymentLink($invoice);

        return response()->json([
            'payment_url' => $paymentUrl,
        ]);
    }

    public function handleWebhook(Request $request, PayPalGateway $paypal): JsonResponse
    {
        $payload = $request->all();
        $eventType = data_get($payload, 'event_type');
        $eventId = data_get($payload, 'id');

        Log::info('PayPalPaymentController.webhook_received', [
            'event_id' => $eventId,
            'event_type' => $eventType,
            'resource_id' => data_get($payload, 'resource.id'),
        ]);

        if (! $paypal->verifyWebhookSignature($request)) {
            Log::warning('PayPalPaymentController.invalid_webhook_signature', [
                'event_id' => $eventId,
                'event_type' => $eventType,
            ]);

            return response()->json(['status' => 'invalid_signature'], 400);
        }

        if (! $eventId) {
            Log::warning('PayPalPaymentController.webhook_missing_event_id', [
                'event_type' => $eventType,
                'resource_id' => data_get($payload, 'resource.id'),
            ]);

            return response()->json(['status' => 'missing_event_id'], 400);
        }

        $eventStoreResponse = $this->storeWebhookEvent($payload, (string) $eventId, (string) $eventType);
        if ($eventStoreResponse) {
            return $eventStoreResponse;
        }

        try {
            if ($eventType === 'CHECKOUT.ORDER.APPROVED') {
                return $this->handleApprovedOrderWebhook($payload, $paypal);
            }

            if ($eventType === 'PAYMENT.CAPTURE.COMPLETED') {
                return $this->handleCompletedCaptureWebhook($payload);
            }

            Log::info('PayPalPaymentController.webhook_ignored', [
                'event_id' => $eventId,
                'event_type' => $eventType,
            ]);
        } catch (\Throwable $e) {
            PayPalWebhookEvent::query()->where('event_id', $eventId)->delete();

            Log::error('PayPalPaymentController.webhook_processing_failed', [
                'event_id' => $eventId,
                'event_type' => $eventType,
                'err' => $e->getMessage(),
            ]);

            return response()->json(['status' => 'processing_failed'], 500);
        }

        return response()->json(['status' => 'ignored']);
    }

    public function handleReturn(Request $request, PayPalGateway $paypal, string $paymentToken): RedirectResponse
    {
        $paymentLink = $this->paymentLinkForToken($paymentToken);
        $invoice = $paymentLink?->invoice;

        if (! $paymentLink || ! $invoice) {
            Log::warning('PayPalPaymentController.return_payment_link_not_found', [
                'payment_token' => $paymentToken,
                'paypal_order_id' => $request->query('token'),
            ]);

            return redirect($this->frontendPaymentUrl($paymentToken, 'payment-cancelled'));
        }

        if ($invoice->status === 'paid') {
            return redirect($this->frontendPaymentUrl($paymentToken, 'payment-success'));
        }

        $orderId = $request->query('token') ?: $paymentLink->paypal_order_id;

        if (! $orderId) {
            Log::warning('PayPalPaymentController.return_missing_order_id', [
                'payment_token' => $paymentToken,
                'payment_link_id' => $paymentLink->id,
            ]);

            return redirect($this->frontendPaymentUrl($paymentToken, 'payment-cancelled'));
        }

        $response = $paypal->capturePayment($orderId);

        Log::info('PayPalPaymentController.return_capture_response', [
            'payment_token' => $paymentToken,
            'payment_link_id' => $paymentLink->id,
            'invoice_id' => $invoice->id,
            'order_id' => $orderId,
            'status' => data_get($response, 'status'),
            'capture_id' => $this->captureIdFromCaptureResponse($response),
        ]);

        if (data_get($response, 'status') !== 'COMPLETED') {
            return redirect($this->frontendPaymentUrl($paymentToken, 'payment-cancelled'));
        }

        $this->completeInvoicePayment($invoice, $response, [
            'event_type' => 'PAYPAL.RETURN.CAPTURE',
            'resource' => ['id' => $orderId],
        ]);

        return redirect($this->frontendPaymentUrl($paymentToken, 'payment-success'));
    }

    public function handleCancel(string $paymentToken): RedirectResponse
    {
        return redirect($this->frontendPaymentUrl($paymentToken, 'payment-cancelled'));
    }

    protected function handleApprovedOrderWebhook(array $payload, PayPalGateway $paypal): JsonResponse
    {
        $orderId = data_get($payload, 'resource.id');

        if (! $orderId) {
            Log::warning('PayPalPaymentController.approved_order_missing_order_id', [
                'event_id' => data_get($payload, 'id'),
            ]);

            return response()->json(['status' => 'ignored']);
        }

        $existingInvoice = $this->resolveInvoiceFromPayPalPayload($payload);

        if ($existingInvoice?->status === 'paid') {
            return response()->json(['status' => 'already_completed']);
        }

        $response = $paypal->capturePayment($orderId);

        Log::info('PayPalPaymentController.capture_payment_response', [
            'event_id' => data_get($payload, 'id'),
            'order_id' => $orderId,
            'status' => data_get($response, 'status'),
            'capture_id' => $this->captureIdFromCaptureResponse($response),
        ]);

        if (data_get($response, 'status') !== 'COMPLETED') {
            return response()->json([
                'status' => 'capture_not_completed',
                'paypal_status' => data_get($response, 'status'),
            ]);
        }

        $invoice = $this->resolveInvoiceFromPayPalPayload($payload, $response);

        if (! $invoice) {
            Log::warning('PayPalPaymentController.invoice_not_found_for_capture', [
                'event_id' => data_get($payload, 'id'),
                'order_id' => $orderId,
                'invoice_id' => $this->invoiceIdFromPayPalPayload($payload, $response),
            ]);

            return response()->json(['status' => 'invoice_not_found']);
        }

        $this->completeInvoicePayment($invoice, $response, $payload);

        return response()->json(['status' => 'captured']);
    }

    protected function handleCompletedCaptureWebhook(array $payload): JsonResponse
    {
        if (data_get($payload, 'resource.status') !== 'COMPLETED') {
            return response()->json([
                'status' => 'capture_not_completed',
                'paypal_status' => data_get($payload, 'resource.status'),
            ]);
        }

        $invoice = $this->resolveInvoiceFromPayPalPayload($payload);

        if (! $invoice) {
            Log::warning('PayPalPaymentController.invoice_not_found_for_completed_capture', [
                'event_id' => data_get($payload, 'id'),
                'capture_id' => data_get($payload, 'resource.id'),
                'order_id' => $this->orderIdFromPayPalPayload($payload),
                'invoice_id' => $this->invoiceIdFromPayPalPayload($payload),
            ]);

            return response()->json(['status' => 'invoice_not_found']);
        }

        $this->completeInvoicePayment($invoice, $payload, $payload);

        return response()->json(['status' => 'completed']);
    }

    protected function resolveInvoiceFromPayPalPayload(array $payload, ?array $captureResponse = null): ?Invoices
    {
        $invoiceId = $this->invoiceIdFromPayPalPayload($payload, $captureResponse);

        if ($invoiceId) {
            return Invoices::query()->find($invoiceId);
        }

        $orderId = $this->orderIdFromPayPalPayload($payload, $captureResponse);

        if ($orderId) {
            return PaymentLink::query()
                ->where('paypal_order_id', $orderId)
                ->first()
                ?->invoice;
        }

        return null;
    }

    protected function completeInvoicePayment(Invoices $invoice, array $captureData, array $webhookPayload = []): void
    {
        $invoice->loadMissing(['paymentLink', 'currency']);

        if ($invoice->status === 'paid') {
            Log::info('PayPalPaymentController.invoice_already_paid_skipped', [
                'invoice_id' => $invoice->id,
                'event_id' => data_get($webhookPayload, 'id'),
                'order_id' => $this->orderIdFromPayPalPayload($webhookPayload, $captureData),
            ]);

            return;
        }

        $captureId = $this->captureIdFromCaptureResponse($captureData)
            ?? data_get($captureData, 'resource.id');
        $orderId = $this->orderIdFromPayPalPayload($webhookPayload, $captureData);
        $paymentData = $this->paymentData($invoice, $captureData, $webhookPayload, $orderId, $captureId);

		$invoice->update([
			'status' => 'paid',
			'amount_due_cents' => 0,
			'paid_at' => now(),
			'pdf_path' => null,
			'pdf_status' => null,
			'pdf_generated_at' => null,
			'pdf_error' => null,
		]);

		$this->paymentReminderService->skipPendingRemindersBecausePaid($invoice->fresh());

        $invoice->paymentLink?->update([
            'paypal_order_id' => $orderId,
            'paypal_capture_id' => $captureId,
        ]);

        PaymentRecord::updateOrCreate(
            ['invoice_id' => $invoice->id],
            [
                'payment_method' => PaymentProvider::PAYPAL->value,
                'data' => $paymentData,
                'token' => $paymentData['token'],
            ]
        );
        Mail::to($invoice->businessProfile?->email)
            ->send(new PaymentSuccessNotificationForBusinessProfileMail($invoice, $paymentData));
        Mail::to($invoice->client?->email)
            ->send(new PaymentSuccessNotificationForClientMail($invoice, $paymentData));

        Log::info('PayPalPaymentController.invoice_payment_completed', [
            'invoice_id' => $invoice->id,
            'order_id' => $orderId,
            'capture_id' => $captureId,
        ]);
    }

    protected function storeWebhookEvent(array $payload, string $eventId, string $eventType): ?JsonResponse
    {
        try {
            PayPalWebhookEvent::create([
                'event_id' => $eventId,
                'type' => $eventType,
                'resource_id' => data_get($payload, 'resource.id'),
                'payload' => $payload,
                'received_at' => now(),
            ]);
        } catch (QueryException $e) {
            if ($this->isDuplicateEventException($e)) {
                Log::info('PayPalPaymentController.duplicate_event_skipped', [
                    'event_id' => $eventId,
                    'event_type' => $eventType,
                ]);

                return response()->json(['status' => 'duplicate']);
            }

            Log::error('PayPalPaymentController.event_persist_failed', [
                'event_id' => $eventId,
                'event_type' => $eventType,
                'err' => $e->getMessage(),
            ]);

            return response()->json(['status' => 'event_persist_failed'], 500);
        }

        return null;
    }

    private function isDuplicateEventException(QueryException $e): bool
    {
        $sqlState = (string) ($e->errorInfo[0] ?? '');
        $driverCode = (string) ($e->errorInfo[1] ?? $e->getCode());
        $message = strtolower($e->getMessage());

        return $sqlState === '23505'
            || in_array($driverCode, ['1062', '2067'], true)
            || (($sqlState === '23000' || $driverCode === '19')
                && (str_contains($message, 'duplicate') || str_contains($message, 'unique')));
    }

    protected function invoiceIdFromPayPalPayload(array $payload, ?array $captureResponse = null): ?int
    {
        $invoiceId = data_get($captureResponse, 'purchase_units.0.custom_id')
            ?? data_get($captureResponse, 'purchase_units.0.payments.captures.0.custom_id')
            ?? data_get($payload, 'resource.purchase_units.0.custom_id')
            ?? data_get($payload, 'resource.custom_id');

        return is_numeric($invoiceId) ? (int) $invoiceId : null;
    }

    protected function orderIdFromPayPalPayload(array $payload, ?array $captureResponse = null): ?string
    {
        $orderId = data_get($payload, 'resource.id');

        if (data_get($payload, 'event_type') === 'PAYMENT.CAPTURE.COMPLETED') {
            $orderId = data_get($payload, 'resource.supplementary_data.related_ids.order_id')
                ?? data_get($payload, 'resource.invoice_id')
                ?? data_get($payload, 'resource.id');
        }

        $captureResponseOrderId = data_get($captureResponse, 'event_type')
            ? null
            : data_get($captureResponse, 'id');

        return $captureResponseOrderId
            ?? data_get($captureResponse, 'purchase_units.0.payments.captures.0.supplementary_data.related_ids.order_id')
            ?? $orderId;
    }

    protected function captureIdFromCaptureResponse(array $captureData): ?string
    {
        return data_get($captureData, 'purchase_units.0.payments.captures.0.id')
            ?? data_get($captureData, 'resource.id');
    }

    protected function paymentData(
        Invoices $invoice,
        array $captureData,
        array $webhookPayload,
        ?string $orderId,
        ?string $captureId
    ): array {
        $amountValue = data_get($captureData, 'purchase_units.0.payments.captures.0.amount.value')
            ?? data_get($captureData, 'resource.amount.value');
        $currency = data_get($captureData, 'purchase_units.0.payments.captures.0.amount.currency_code')
            ?? data_get($captureData, 'resource.amount.currency_code')
            ?? $invoice->currency?->code;

        return [
            'invoice_number' => $invoice->invoice_number,
            'invoice_payment_method' => PaymentProvider::PAYPAL->value,
            'paypal_order_id' => $orderId,
            'paypal_capture_id' => $captureId,
            'amount_paid' => is_numeric($amountValue) ? (int) round((float) $amountValue * 100) : null,
            'currency' => $currency,
            'payment_date' => now(),
            'token' => (string) ($invoice->paymentLink?->token ?? ''),
            'webhook_event_id' => data_get($webhookPayload, 'id'),
            'webhook_event_type' => data_get($webhookPayload, 'event_type'),
        ];
    }

    protected function paymentLinkForToken(string $paymentToken): ?PaymentLink
    {
        return PaymentLink::query()
            ->with(['invoice.paymentLink', 'invoice.currency'])
            ->where('token', $paymentToken)
            ->first();
    }

    protected function frontendPaymentUrl(string $paymentToken, string $status): string
    {
        if ($status === 'payment-cancelled') {
            return rtrim((string) config('app.frontend_url'), '/')."/app/invoices/{$paymentToken}/paypal/{$status}";
        }

        return rtrim((string) config('app.frontend_url'), '/')."/app/invoices/{$paymentToken}/{$status}";
    }
}
