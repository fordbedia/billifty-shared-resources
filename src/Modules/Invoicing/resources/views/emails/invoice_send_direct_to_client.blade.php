<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>
        Invoice {{ $invoice->invoice_number }}
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
            background-color: #f3f4f6;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            color: #111827;
        }

        .wrapper {
            width: 100%;
            background-color: #f3f4f6;
            padding: 24px 0;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #e5e7eb;
        }

        .header {
            padding: 20px 24px;
            background-color: #111827;
            color: #f9fafb;
        }

        .header-title {
            font-size: 18px;
            font-weight: 600;
            margin: 0 0 4px 0;
        }

        .header-subtitle {
            font-size: 14px;
            margin: 0;
            color: #e5e7eb;
        }

        .content {
            padding: 24px;
        }

        .content p {
            font-size: 14px;
            line-height: 1.6;
            margin: 0 0 12px 0;
        }

        .content strong {
            font-weight: 600;
        }

        .highlight-box {
            margin: 16px 0;
            padding: 12px 14px;
            background-color: #f9fafb;
            border-radius: 6px;
            border: 1px solid #e5e7eb;
            font-size: 14px;
        }

        .highlight-label {
            font-weight: 600;
            display: inline-block;
            width: 120px;
        }

        .user-message-wrapper {
            margin: 20px 0 16px 0;
        }

        .user-message-label {
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: #6b7280;
            margin-bottom: 6px;
        }

        .user-message-box {
            padding: 14px 16px;
            background: linear-gradient(135deg, #eef2ff, #eff6ff);
            border-radius: 10px;
            border: 1px solid #c7d2fe;
            font-size: 14px;
            line-height: 1.7;
            color: #111827;
        }

        .user-message-box p {
            margin: 0 0 10px 0;
        }

        .user-message-box p:last-child {
            margin-bottom: 0;
        }

        .footer {
            padding: 16px 24px 20px 24px;
            font-size: 11px;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
            background-color: #f9fafb;
        }

        .footer p {
            margin: 0 0 6px 0;
        }
    </style>
</head>

<body>
<div class="wrapper">
    <div class="container">

        {{-- Header --}}
        <div class="header">
            @php
                $clientName = $invoice->client->name ?? 'there';
                $businessName = $invoice->businessProfile->legal_name
                    ?? $invoice->businessProfile->name
                    ?? $invoice->user->name
                    ?? config('app.name', 'Billifty');
            @endphp

            <h1 class="header-title">
                Invoice from {{ $businessName }}
            </h1>
            <p class="header-subtitle">
                Invoice #{{ $invoice->invoice_number }} has been sent to you.
            </p>
        </div>

        {{-- Main Content --}}
        <div class="content">
            @php
                $clientEmail = $invoice->client->email ?? null;
            @endphp

            <p>Hi {{ $clientName }},</p>

            <p>
                {{ $businessName }} has sent you an invoice:
                <strong>#{{ $invoice->invoice_number }}</strong>.
                The PDF is attached to this email for your review and records.
            </p>

            <p>
                Inside the invoice, you’ll find a breakdown of the products or services provided,
                quantities, pricing details, and the total amount due. If anything looks unclear
                or if you have questions about specific charges, please don’t hesitate to reach out
                directly to {{ $businessName }}.
            </p>

            {{-- Stand-out message from the sender (TipTap content) --}}
            @if (!empty($userMessage ?? null))
                <div class="user-message-wrapper">
                    <div class="user-message-label">A note from {{ $businessName }}</div>
                    <div class="user-message-box">
                        {!! $userMessage !!}
                    </div>
                </div>
            @endif

            <div class="highlight-box">
                <div>
                    <span class="highlight-label">Invoice number:</span>
                    <strong>#{{ $invoice->invoice_number }}</strong>
                </div>

                @if($businessName)
                    <div>
                        <span class="highlight-label">From:</span>
                        <span>{{ $businessName }}</span>
                    </div>
                @endif

                @if($clientName)
                    <div>
                        <span class="highlight-label">To:</span>
                        <span>{{ $clientName }}</span>
                    </div>
                @endif

                @if($clientEmail)
                    <div>
                        <span class="highlight-label">Email:</span>
                        <span>{{ $clientEmail }}</span>
                    </div>
                @endif
            </div>

            <p>
                Please review the invoice at your convenience. If the invoice includes a due date or
                payment instructions, we kindly ask that you follow those details to ensure a smooth
                payment process.
            </p>

            <p>
                Thank you for your time and for your business.
            </p>

            <p>
                Best regards,<br>
                {{ $businessName }}
            </p>
        </div>

        {{-- Footer --}}
        <div class="footer">
            <p>
                You’re receiving this email because {{ $businessName }} issued an invoice to you.
            </p>
            <p>{{ config('app.name', 'Billifty') }} — {{ config('app.url') }}</p>
        </div>

    </div>
</div>
</body>
</html>
