<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>
        Invoice {{ $invoice->invoice_number }} reminder
        @if($invoice->businessProfile?->legal_name)
            from {{ $invoice->businessProfile->legal_name }}
        @else
            from your service provider
        @endif
    </title>
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

        .user-message-wrapper {
            margin: 24px 0;
        }

        .user-message-label {
            margin: 0 0 10px 2px;
            color: #6f8aa0;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.09em;
            text-transform: uppercase;
        }

        .user-message-box {
            padding: 18px 20px;
            border: 1px solid #cfe1f1;
            border-radius: 20px;
            background: linear-gradient(180deg, #f5fafe 0%, #eef6fd 100%);
            color: #294255;
            font-size: 15px;
            line-height: 1.8;
        }

        .user-message-box p {
            margin: 0 0 10px 0;
        }

        .user-message-box p:last-child {
            margin-bottom: 0;
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

        .button-wrapper {
            margin: 24px 0 8px 0;
        }

        .button {
            display: inline-block;
            padding: 12px 18px;
            border-radius: 12px;
            background-color: #256f9f;
            color: #ffffff !important;
            text-decoration: none;
            font-size: 14px;
            font-weight: 700;
            line-height: 1.4;
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
    $logoPath = public_path('billifty.png');
    $logoAsset = asset('billifty.png');
    $logoSrc = isset($message) && $logoPath ? $message->embed($logoPath) : $logoAsset;
    $clientName = $invoice->client->name ?? 'there';
    $clientEmail = $invoice->client->email ?? null;
    $businessName = $invoice->businessProfile->legal_name
        ?? $invoice->businessProfile->name
        ?? $invoice->user->name
        ?? config('app.name', 'Billifty');
    $currencySymbol = $invoice->currency->symbol ?? '$';
    $amountDue = $currencySymbol . number_format(((int) $invoice->amount_due_cents) / 100, 2);
    $dueDate = $invoice->due_on ? \Illuminate\Support\Carbon::parse($invoice->due_on)->toFormattedDateString() : null;
    $offsetDays = (int) $reminder->offset_days;
    $heading = $offsetDays < 0
        ? 'Invoice due soon'
        : ($offsetDays === 0 ? 'Invoice due today' : 'Invoice overdue');
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

            <div class="eyebrow">Payment Reminder</div>
            <h1 class="hero-title">{{ $heading }}</h1>
            <p class="hero-subtitle">
                This is a friendly reminder for invoice #{{ $invoice->invoice_number }} from {{ $businessName }}.
            </p>

            <div class="hero-meta">
                <div class="hero-meta-row">
                    <div class="hero-meta-item">
                        <p class="meta-label">Invoice Number</p>
                        <p class="meta-value">#{{ $invoice->invoice_number }}</p>
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
                    This is a friendly reminder from {{ $businessName }} about invoice
                    <strong>#{{ $invoice->invoice_number }}</strong>.
                </p>

                <p>
                    If payment has already been sent, please disregard this reminder. Otherwise,
                    please review the invoice when convenient.
                </p>

                <div class="details-card">
                    <h2 class="details-title">Invoice summary</h2>

                    <div class="detail-row">
                        <div class="detail-label">Invoice number</div>
                        <div class="detail-value"><strong>#{{ $invoice->invoice_number }}</strong></div>
                    </div>

                    <div class="detail-row">
                        <div class="detail-label">Amount due</div>
                        <div class="detail-value">{{ $amountDue }}</div>
                    </div>

                    @if($dueDate)
                        <div class="detail-row">
                            <div class="detail-label">Due date</div>
                            <div class="detail-value">{{ $dueDate }}</div>
                        </div>
                    @endif

                    @if($businessName)
                        <div class="detail-row">
                            <div class="detail-label">From</div>
                            <div class="detail-value">{{ $businessName }}</div>
                        </div>
                    @endif

                    @if($clientName)
                        <div class="detail-row">
                            <div class="detail-label">To</div>
                            <div class="detail-value">{{ $clientName }}</div>
                        </div>
                    @endif

                    @if($clientEmail)
                        <div class="detail-row">
                            <div class="detail-label">Email</div>
                            <div class="detail-value">{{ $clientEmail }}</div>
                        </div>
                    @endif
                </div>

                @if($publicInvoiceUrl)
                    <div class="button-wrapper">
                        <a class="button" href="{{ $publicInvoiceUrl }}">View Invoice</a>
                    </div>
                @endif

                <p>
                    Thank you for your time and for your business.
                </p>

                <div class="signoff">
                    <p>
                        Best regards,<br>
                        {{ $businessName }}
                    </p>
                </div>
            </div>
        </div>

        <div class="footer">
            <p>
                You’re receiving this reminder because {{ $businessName }} issued an invoice to you.
            </p>
            <p>{{ config('app.name', 'Billifty') }} — {{ config('app.url') }}</p>
        </div>

    </div>
</div>
</body>
</html>
