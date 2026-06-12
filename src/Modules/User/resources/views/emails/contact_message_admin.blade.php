<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>New contact message - {{ config('app.name', 'Billifty') }}</title>
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

            <div class="eyebrow">Contact request</div>
            <h1 class="hero-title">New message from {{ $contactMessage->name }}</h1>
            <p class="hero-subtitle">
                A user submitted the contact form from {{ config('app.name', 'Billifty') }}.
            </p>

            <div class="hero-meta">
                <div class="hero-meta-row">
                    <div class="hero-meta-item">
                        <p class="meta-label">Subject</p>
                        <p class="meta-value">{{ $contactMessage->subject }}</p>
                    </div>
                    <div class="hero-meta-item">
                        <p class="meta-label">Sender</p>
                        <p class="meta-value">{{ $contactMessage->email }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="content">
            <div class="body-card">
                <p>
                    <strong>{{ $contactMessage->name }}</strong> sent a new contact message.
                    Reply directly to this email to respond to the sender.
                </p>

                <div class="user-message-wrapper">
                    <div class="user-message-label">Message</div>
                    <div class="user-message-box">
                        {!! nl2br(e($contactMessage->message)) !!}
                    </div>
                </div>

                <div class="details-card">
                    <h2 class="details-title">Contact details</h2>

                    <div class="detail-row">
                        <div class="detail-label">Name</div>
                        <div class="detail-value">{{ $contactMessage->name }}</div>
                    </div>

                    <div class="detail-row">
                        <div class="detail-label">Email</div>
                        <div class="detail-value">{{ $contactMessage->email }}</div>
                    </div>

                    <div class="detail-row">
                        <div class="detail-label">Subject</div>
                        <div class="detail-value"><strong>{{ $contactMessage->subject }}</strong></div>
                    </div>

                    @if($contactMessage->user_id)
                        <div class="detail-row">
                            <div class="detail-label">User ID</div>
                            <div class="detail-value">{{ $contactMessage->user_id }}</div>
                        </div>
                    @endif

                    @if($contactMessage->created_at)
                        <div class="detail-row">
                            <div class="detail-label">Submitted</div>
                            <div class="detail-value">{{ $contactMessage->created_at->format('M j, Y g:i A') }}</div>
                        </div>
                    @endif
                </div>

                <div class="signoff">
                    <p>
                        This notification was generated automatically by {{ config('app.name', 'Billifty') }}.
                    </p>
                </div>
            </div>
        </div>

        <div class="footer">
            <p>
                You’re receiving this email because you are configured as the contact message recipient.
            </p>
            <p>{{ config('app.name', 'Billifty') }} — {{ config('app.url') }}</p>
        </div>

    </div>
</div>
</body>
</html>
