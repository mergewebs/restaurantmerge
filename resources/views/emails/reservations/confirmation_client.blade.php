<!DOCTYPE html>
<html lang="{{ $locale }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('emails.confirmation.title', [], $locale) }}</title>
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
        .greeting {
            font-size: 18px;
            font-weight: bold;
            color: #ffffff;
            margin-bottom: 10px;
        }
        .intro {
            font-size: 16px;
            line-height: 1.5;
            color: #ffffff;
            margin-bottom: 30px;
        }
        .details-box {
            background-color: #055f4f;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 30px;
        }
        .detail-item {
            margin-bottom: 15px;
            display: flex;
            align-items: flex-start;
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
        .contact-info {
            font-size: 16px;
            line-height: 1.6;
            color: #ffffff;
            margin-bottom: 30px;
        }
        .contact-label {
            color: #fac021;
            font-weight: bold;
            display: block;
            margin-top: 10px;
        }
        .contact-link {
            color: white !important;
            text-decoration: underline;
        }
        .closing {
            font-size: 16px;
            font-style: italic;
            color: #ffffff;
            margin-bottom: 40px;
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

        <h1>{{ __('emails.confirmation.title', [], $locale) }}</h1>

        <div class="divider"></div>

        <div class="greeting">
            {{ __('emails.confirmation.greeting', ['name' => $reservation->customer_name], $locale) }}
        </div>

        <div class="intro">
            {!! __('emails.confirmation.intro', [], $locale) !!}
        </div>

        <div class="details-box">
            <div class="detail-item">
                <span class="detail-label">📅 {{ __('emails.confirmation.date_time', [], $locale) }}:</span>
                <span class="detail-value">
                    {{ \Carbon\Carbon::parse($reservation->reservation_time)->format('d/m/Y') }} 
                    {{ \Carbon\Carbon::parse($reservation->reservation_time)->format('H:i') }}
                </span>
            </div>
            <div class="detail-item">
                <span class="detail-label">📍 {{ __('emails.confirmation.location', [], $locale) }}:</span>
                <span class="detail-value">
                    <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode(__('emails.confirmation.location_address', [], $locale)) }}" class="contact-link">
                        {{ __('emails.confirmation.location_address', [], $locale) }}
                    </a>
                </span>
            </div>
            <div class="detail-item">
                <span class="detail-label">🔢 {{ __('emails.confirmation.reservation_number', [], $locale) }}:</span>
                <span class="detail-value">#{{ $reservation->id }}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">👥 {{ __('emails.confirmation.guests', [], $locale) }}:</span>
                <span class="detail-value">{{ $reservation->party_size }}</span>
            </div>
        </div>

        <div class="contact-info">
            <p>{{ __('emails.confirmation.contact_intro', [], $locale) }}</p>
            
            <span class="contact-label">{{ __('emails.confirmation.email_label', [], $locale) }}:</span>
            <a href="mailto:{{ __('emails.confirmation.contact_email', [], $locale) }}" class="contact-link">{{ __('emails.confirmation.contact_email', [], $locale) }}</a>

            <span class="contact-label">{{ __('emails.confirmation.phone_label', [], $locale) }}:</span>
            <a href="tel:{{ str_replace(' ', '', __('emails.confirmation.contact_phone', [], $locale)) }}" class="contact-link">{{ __('emails.confirmation.contact_phone', [], $locale) }}</a>

            <span class="contact-label">{{ __('emails.confirmation.whatsapp_label', [], $locale) }}:</span>
            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', __('emails.confirmation.contact_whatsapp', [], $locale)) }}" class="contact-link">{{ __('emails.confirmation.contact_whatsapp', [], $locale) }}</a>
        </div>

        <div class="closing">
            {{ __('emails.confirmation.closing', [], $locale) }}
        </div>

        <div class="divider"></div>

        <div class="footer">
            <div class="footer-name">{{ __('emails.confirmation.footer_name', [], $locale) }}</div>
            <a href="https://{{ __('emails.confirmation.footer_url', [], $locale) }}" class="footer-link">{{ __('emails.confirmation.footer_url', [], $locale) }}</a>
        </div>
    </div>
</body>
</html>

