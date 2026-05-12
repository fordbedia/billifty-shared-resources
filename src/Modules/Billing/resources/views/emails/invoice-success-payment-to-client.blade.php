<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment confirmation for invoice {{ $invoice->invoice_number }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f4f8fc;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            color: #1f3142;
        }

        .wrapper {
            width: 100%;
            background:
                radial-gradient(circle at top, #eaf4fe 0%, #f4f8fc 42%, #f4f8fc 100%);
            padding: 32px 16px;
        }

        .container {
            max-width: 640px;
            margin: 0 auto;
            background-color: #ffffff;
            border: 1px solid #dbe7f2;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 22px 54px -38px rgba(40, 81, 109, 0.45);
        }

        .hero {
            padding: 28px 28px 24px 28px;
            background:
                linear-gradient(180deg, #eff7ff 0%, #e7f1fb 100%);
            border-bottom: 1px solid #d7e6f4;
        }

        .logo-wrap {
            margin-bottom: 22px;
        }

        .eyebrow {
            display: inline-block;
            margin: 0 0 14px 0;
            padding: 6px 12px;
            border: 1px solid #b9d7ee;
            border-radius: 999px;
            background-color: rgba(255, 255, 255, 0.76);
            color: #3578a6;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .hero-title {
            margin: 0;
            color: #22384c;
            font-size: 31px;
            font-weight: 300;
            line-height: 1.16;
            letter-spacing: -0.03em;
        }

        .hero-subtitle {
            margin: 12px 0 0 0;
            max-width: 500px;
            color: #567086;
            font-size: 15px;
            line-height: 1.65;
        }

        .hero-meta {
            margin-top: 22px;
            padding: 16px 18px;
            border: 1px solid #cfe0ef;
            border-radius: 18px;
            background-color: rgba(255, 255, 255, 0.88);
        }

        .hero-meta-row {
            font-size: 0;
        }

        .hero-meta-item {
            display: inline-block;
            width: 50%;
            vertical-align: top;
            box-sizing: border-box;
            padding-right: 12px;
        }

        .meta-label {
            margin: 0 0 6px 0;
            color: #6f8aa0;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .meta-value {
            margin: 0;
            color: #23384b;
            font-size: 16px;
            font-weight: 600;
            line-height: 1.45;
        }

        .content {
            padding: 28px;
        }

        .content p {
            margin: 0 0 16px 0;
            color: #385165;
            font-size: 15px;
            line-height: 1.8;
        }

        .content strong {
            color: #22384c;
            font-weight: 700;
        }

        .body-card {
            padding: 22px 22px 20px 22px;
            border: 1px solid #e2ebf3;
            border-radius: 20px;
            background-color: #ffffff;
            box-shadow: 0 16px 36px -34px rgba(43, 84, 111, 0.5);
        }

        .details-card {
            margin: 24px 0;
            padding: 20px;
            border: 1px solid #d9e6f1;
            border-radius: 20px;
            background-color: #f8fbfe;
        }

        .details-title {
            margin: 0 0 16px 0;
            color: #294459;
            font-size: 16px;
            font-weight: 600;
            line-height: 1.4;
        }

        .detail-row {
            padding: 12px 0;
            border-top: 1px solid #e5eef6;
            font-size: 0;
        }

        .detail-row:first-of-type {
            border-top: 0;
            padding-top: 0;
        }

        .detail-label,
        .detail-value {
            display: inline-block;
            vertical-align: top;
            box-sizing: border-box;
            font-size: 14px;
            line-height: 1.65;
        }

        .detail-label {
            width: 36%;
            color: #6b859a;
            font-weight: 600;
        }

        .detail-value {
            width: 64%;
            color: #22384c;
            font-weight: 500;
        }

        .button-wrap {
            margin: 24px 0 2px 0;
        }

        .button {
            display: inline-block;
            padding: 12px 18px;
            border-radius: 999px;
            background-color: #2f7ea9;
            color: #ffffff !important;
            font-size: 14px;
            font-weight: 700;
            line-height: 1.2;
            text-decoration: none;
        }

        .signoff {
            margin-top: 22px;
            padding-top: 18px;
            border-top: 1px solid #e6edf4;
        }

        .footer {
            padding: 18px 28px 26px 28px;
            border-top: 1px solid #e1ebf3;
            background-color: #f8fbfe;
        }

        .footer p {
            margin: 0 0 6px 0;
            color: #6c8294;
            font-size: 12px;
            line-height: 1.65;
        }

        .footer p:last-child {
            margin-bottom: 0;
        }

        @media only screen and (max-width: 640px) {
            .wrapper {
                padding: 18px 10px;
            }

            .hero,
            .content,
            .footer {
                padding-left: 20px;
                padding-right: 20px;
            }

            .hero-title {
                font-size: 26px;
            }

            .hero-meta-item,
            .detail-label,
            .detail-value {
                display: block;
                width: 100%;
                padding-right: 0;
            }

            .detail-value {
                margin-top: 3px;
            }
        }
    </style>
</head>

<body>
@php
    $paymentData = $paymentData ?? [];
    $logoPath = public_path('billifty.png');
    $logoAsset = asset('billifty.png');
    $logoSrc = isset($message) && file_exists($logoPath) ? $message->embed($logoPath) : $logoAsset;

    $clientName = $invoice->client->name ?? 'there';
    $clientEmail = $invoice->client->email ?? null;
    $businessName = $invoice->businessProfile->legal_name
        ?? $invoice->businessProfile->name
        ?? $invoice->user->name
        ?? config('app.name', 'Billifty');
    $businessEmail = $invoice->businessProfile->email ?? $invoice->user->email ?? null;
    $currencySymbol = $paymentData['currency_symbol'] ?? '';
    $currencyCode = strtoupper($paymentData['currency'] ?? '');
    $amountPaid = isset($paymentData['amount_paid'])
        ? $currencySymbol . number_format(((float) $paymentData['amount_paid']) / 100, 2) . ($currencyCode ? " {$currencyCode}" : '')
        : null;
    $paymentDate = !empty($paymentData['payment_date'])
        ? \Illuminate\Support\Carbon::parse($paymentData['payment_date'])->format('M j, Y g:i A')
        : null;
    $paymentMethod = trim(collect([
        $paymentData['card_brand'] ?? $paymentData['payment_method'] ?? null,
        !empty($paymentData['card_last4'] ?? null) ? 'ending in ' . $paymentData['card_last4'] : null,
    ])->filter()->implode(' '));
@endphp
<div class="wrapper">
    <div class="container">

        <div class="hero">
            <div class="logo-wrap">
                <img
                    src="{{ $logoSrc }}"
                    alt="Billifty Logo"
                    style="display: block; max-width: 188px; height: auto;"
                >
            </div>

            <div class="eyebrow">Payment Confirmation</div>
            <h1 class="hero-title">Thanks, your payment was successful</h1>
            <p class="hero-subtitle">
                Your payment for invoice #{{ $invoice->invoice_number }} from {{ $businessName }} has been received.
            </p>

            <div class="hero-meta">
                <div class="hero-meta-row">
                    <div class="hero-meta-item">
                        <p class="meta-label">Amount Paid</p>
                        <p class="meta-value">{{ $amountPaid ?? 'Payment received' }}</p>
                    </div>
                    <div class="hero-meta-item">
                        <p class="meta-label">From</p>
                        <p class="meta-value">{{ $businessName }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="content">
            <div class="body-card">
                <p>Hi {{ $clientName }},</p>

                <p>
                    We received your payment for <strong>invoice #{{ $invoice->invoice_number }}</strong>
                    from <strong>{{ $businessName }}</strong>. Please keep this email as confirmation
                    of your payment.
                </p>

                <div class="details-card">
                    <h2 class="details-title">Payment summary</h2>

                    <div class="detail-row">
                        <div class="detail-label">Invoice number</div>
                        <div class="detail-value"><strong>#{{ $invoice->invoice_number }}</strong></div>
                    </div>

                    @if($amountPaid)
                        <div class="detail-row">
                            <div class="detail-label">Amount paid</div>
                            <div class="detail-value">{{ $amountPaid }}</div>
                        </div>
                    @endif

                    <div class="detail-row">
                        <div class="detail-label">Paid to</div>
                        <div class="detail-value">{{ $businessName }}</div>
                    </div>

                    @if($clientEmail)
                        <div class="detail-row">
                            <div class="detail-label">Email</div>
                            <div class="detail-value">{{ $clientEmail }}</div>
                        </div>
                    @endif

                    @if($paymentDate)
                        <div class="detail-row">
                            <div class="detail-label">Payment date</div>
                            <div class="detail-value">{{ $paymentDate }}</div>
                        </div>
                    @endif

                    @if($paymentMethod)
                        <div class="detail-row">
                            <div class="detail-label">Payment method</div>
                            <div class="detail-value">{{ $paymentMethod }}</div>
                        </div>
                    @endif

                    @if(!empty($paymentData['stripe_payment_intent_id'] ?? null))
                        <div class="detail-row">
                            <div class="detail-label">Transaction ID</div>
                            <div class="detail-value">{{ $paymentData['stripe_payment_intent_id'] }}</div>
                        </div>
                    @endif
                </div>

                <p>
                    If you have questions about this invoice or the services provided, please contact
                    {{ $businessName }}@if($businessEmail) at <strong>{{ $businessEmail }}</strong>@endif.
                </p>

                @if(!empty($paymentData['receipt_url'] ?? null))
                    <div class="button-wrap">
                        <a class="button" href="{{ $paymentData['receipt_url'] }}">View receipt</a>
                    </div>
                @endif

                <div class="signoff">
                    <p>
                        Best regards,<br>
                        {{ $businessName }}
                    </p>
                </div>
            </div>
        </div>

        <div class="footer">
            <p>You're receiving this email because you paid invoice #{{ $invoice->invoice_number }} from {{ $businessName }}.</p>
            <p>{{ config('app.name', 'Billifty') }} - {{ config('app.url') }}</p>
        </div>

    </div>
</div>
</body>
</html>
