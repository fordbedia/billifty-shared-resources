<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verify your Billifty account</title>
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
            background: radial-gradient(circle at top, #ebf5ff 0%, #f4f8fc 42%, #f4f8fc 100%);
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
            background: linear-gradient(180deg, #eff7ff 0%, #e7f1fb 100%);
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

        .content-card {
            padding: 22px 22px 20px 22px;
            border: 1px solid #e2ebf3;
            border-radius: 20px;
            background-color: #ffffff;
            box-shadow: 0 16px 36px -34px rgba(43, 84, 111, 0.5);
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

        .summary-card {
            margin: 24px 0;
            padding: 20px;
            border: 1px solid #d9e6f1;
            border-radius: 20px;
            background-color: #f8fbfe;
        }

        .summary-title {
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

        .action-wrap {
            margin: 30px 0 26px 0;
        }

        .button {
            display: inline-block;
            padding: 14px 24px;
            border-radius: 10px;
            background-color: #1d84c6;
            color: #ffffff !important;
            font-size: 15px;
            font-weight: 600;
            line-height: 1;
            text-decoration: none;
        }

        .subcopy {
            margin-top: 28px;
            padding-top: 24px;
            border-top: 1px solid #e1ebf3;
        }

        .subcopy p {
            color: #6c8294;
            font-size: 14px;
        }

        .subcopy a,
        .footer a {
            color: #2f78ae;
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
            text-align: center;
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

            .detail-label,
            .detail-value {
                display: block;
                width: 100%;
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

            <div class="eyebrow">Email Verification</div>
            <h1 class="hero-title">Welcome to Billifty!</h1>
            <p class="hero-subtitle">
                Please verify your email address to activate your account and keep your billing information secure.
            </p>

            <div class="hero-meta">
                <p class="meta-label">Verification Email</p>
                <p class="meta-value">{{ $recipientEmail ?: 'Your registered email address' }}</p>
            </div>
        </div>

        <div class="content">
            <div class="content-card">
                <p>Hi {{ $recipientName }},</p>

                <p>
                    Please confirm your email address to finish setting up your Billifty account.
                    Once verified, you’ll be able to access your workspace and continue managing invoices with confidence.
                </p>

                <p>
                    This extra step helps us protect your account and make sure important invoice and billing updates
                    reach the right inbox.
                </p>

                <div class="summary-card">
                    <h2 class="summary-title">What happens next</h2>

                    <div class="detail-row">
                        <div class="detail-label">Step</div>
                        <div class="detail-value">Verify your email address</div>
                    </div>

                    <div class="detail-row">
                        <div class="detail-label">Status</div>
                        <div class="detail-value">Pending confirmation</div>
                    </div>

                    @if($recipientEmail)
                        <div class="detail-row">
                            <div class="detail-label">Email</div>
                            <div class="detail-value">{{ $recipientEmail }}</div>
                        </div>
                    @endif
                </div>

                <div class="action-wrap">
                    <a href="{{ $verificationUrl }}" class="button" target="_blank" rel="noopener">
                        Verify Email Address
                    </a>
                </div>

                <p>
                    If you did not create a Billifty account, you can safely ignore this email.
                </p>

                <div class="subcopy">
                    <p>
                        If you're having trouble clicking the "Verify Email Address" button, copy and paste the URL below into your web browser:
                    </p>
                    <p style="word-break: break-all;">
                        <a href="{{ $verificationUrl }}">{{ $verificationUrl }}</a>
                    </p>
                </div>
            </div>
        </div>

        <div class="footer">
            <p>© {{ date('Y') }} {{ config('app.name', 'Billifty') }}. All rights reserved.</p>
        </div>
    </div>
</div>
</body>
</html>
