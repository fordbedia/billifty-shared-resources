<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Copy of Invoice {{ $invoice->invoice_number }}</title>
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
            <h1 class="header-title">Your Invoice Copy Has Arrived</h1>
            <p class="header-subtitle">A personal copy has been sent to your inbox.</p>
        </div>

        {{-- Main Content --}}
        <div class="content">
            @php
                $businessName  = $invoice->businessProfile->legal_name ?? null;
                $contactName   = $invoice->businessProfile->name
                    ?? $invoice->user->name
                    ?? 'there';
                $recipientEmail = $invoice->businessProfile->email
                    ?? $invoice->user->email
                    ?? 'your email address';
            @endphp

            <p>Hi {{ $contactName }},</p>

            <p>
                Your invoice copy is ready! 🎉
                We’ve successfully delivered a personal copy of
                <strong>invoice #{{ $invoice->invoice_number }}</strong>
                to your email address:
                <strong>{{ $recipientEmail }}</strong>.
            </p>

            <p>
                This email is for your records as the sender.
                You can store it, review it, or forward it internally as needed.
                The attached PDF includes all invoice details such as client information,
                line items, totals, and payment instructions.
            </p>

            <div class="highlight-box">
                <div>
                    <span class="highlight-label">Invoice number:</span>
                    <strong>#{{ $invoice->invoice_number }}</strong>
                </div>
                @if($businessName)
                    <div>
                        <span class="highlight-label">Business:</span>
                        <span>{{ $businessName }}</span>
                    </div>
                @endif
                <div>
                    <span class="highlight-label">Sent to:</span>
                    <span>{{ $recipientEmail }}</span>
                </div>
            </div>

            <p>
                If you need to make adjustments, simply update the invoice in Billifty
                and regenerate a new copy at any time.
            </p>

            <p>
                Thank you for using Billifty to manage and organize your invoicing.
                Keeping clear records is an essential part of running a professional business,
                and we’re here to make that easier for you every day.
            </p>

            <p>
                Warm regards,<br>
                The Billifty Team
            </p>
        </div>

        {{-- Footer --}}
        <div class="footer">
            <p>You’re receiving this message because you requested a personal copy of the invoice.</p>
            <p>{{ config('app.name', 'Billifty') }} — {{ config('app.url') }}</p>
        </div>

    </div>
</div>
</body>
</html>
