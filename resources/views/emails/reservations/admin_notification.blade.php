<!DOCTYPE html>
<html lang="{{ $locale }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('emails.admin.title', [], $locale) }}</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #088972;
            color: #ffffff;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #088972;
            padding: 40px 20px;
        }
        .logo {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo img {
            max-width: 120px;
            border-radius: 50%;
            border: 3px solid #fac021;
        }
        h1 {
            color: #fac021;
            text-align: center;
            font-size: 28px;
            margin-bottom: 20px;
        }
        .divider {
            border-top: 1px solid #fac021;
            margin: 20px 0;
        }
        .intro {
            font-size: 18px;
            font-weight: bold;
            color: #ffffff;
            margin-bottom: 20px;
            text-align: center;
        }
        .details-box {
            background-color: #055f4f;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 30px;
        }
        .detail-item {
            margin-bottom: 15px;
            font-size: 16px;
            line-height: 1.5;
        }
        .detail-item:last-child {
            margin-bottom: 0;
        }
        .detail-label {
            color: #fac021;
            font-weight: bold;
            margin-right: 5px;
        }
        .detail-value {
            color: #ffffff;
        }
        .button-wrap {
            text-align: center;
            margin-top: 30px;
            margin-bottom: 30px;
        }
        .button {
            display: inline-block;
            background-color: #fac021;
            color: #121212 !important;
            font-weight: bold;
            text-decoration: none;
            padding: 12px 30px;
            border-radius: 9999px;
            font-size: 16px;
            text-transform: uppercase;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .footer {
            padding-top: 20px;
            text-align: center;
            font-size: 14px;
            color: #ffffff;
        }
        .footer-name {
            font-weight: bold;
            margin-bottom: 10px;
        }
        .footer-link {
            color: white !important;
            text-decoration: none;
        }
        a {
            color: white !important;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            <img src="https://koyabcn.es/wp-content/uploads/2025/07/koya-logo-e1744122653245.jpg" alt="Koya Smash Burger & Bol Logo">
        </div>

        <h1>{{ __('emails.admin.title', [], $locale) }}</h1>

        <div class="divider"></div>

        <div class="intro">
            {{ __('emails.admin.details', [], $locale) }}
        </div>

        <div class="details-box">
            <div class="detail-item">
                <span class="detail-label">👤 {{ __('emails.admin.name', [], $locale) }}:</span>
                <span class="detail-value">{{ $reservation->customer_name }}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">📅 {{ __('emails.admin.datetime', [], $locale) }}:</span>
                <span class="detail-value">
                    {{ \Carbon\Carbon::parse($reservation->reservation_time)->format('d/m/Y H:i') }}
                </span>
            </div>
            <div class="detail-item">
                <span class="detail-label">👥 {{ __('emails.admin.people', [], $locale) }}:</span>
                <span class="detail-value">{{ $reservation->party_size }}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">📞 {{ __('emails.admin.phone', [], $locale) }}:</span>
                <span class="detail-value">{{ $reservation->customer_phone ?? __('emails.admin.none', [], $locale) }}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">📧 {{ __('emails.admin.email', [], $locale) }}:</span>
                <span class="detail-value">{{ $reservation->customer_email ?? __('emails.admin.none', [], $locale) }}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">📝 {{ __('emails.admin.notes', [], $locale) }}:</span>
                <span class="detail-value">{{ $reservation->notes ?? __('emails.admin.none', [], $locale) }}</span>
            </div>
        </div>

        <div class="button-wrap">
            <a href="{{ route('dashboard') }}" class="button">
                {{ __('Dashboard') }}
            </a>
        </div>

        <div class="divider"></div>

        <div class="footer">
            <div class="footer-name">Koya Smash Burger & Bol</div>
            <a href="https://koyabcn.es" class="footer-link">koyabcn.es</a>
        </div>
    </div>
</body>
</html>

