<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>{{ __('emails.admin.title', [], 'en') }}</title>
    <style>
        body { margin: 0; padding: 12px; font-family: Arial, sans-serif; background: #f4f4f4; color: #1f2937; }
        .details { max-width: 560px; margin: 0 auto; background: #fff; border: 1px solid #e5e7eb; border-radius: 8px; overflow: hidden; }
        .row { padding: 9px 12px; border-bottom: 1px solid #e5e7eb; font-size: 15px; line-height: 1.3; }
        .row:last-child { border-bottom: 0; }
        .label { display: inline-block; min-width: 105px; color: #055f4f; font-weight: bold; }
        .value { color: #1f2937; overflow-wrap: anywhere; }
    </style>
</head>
<body>
    <div class='details'>
        <div class='row'><span class='label'>{{ __('emails.admin.name', [], 'en') }}:</span> <span class='value'>{{ $reservation->customer_name }}</span></div>
        <div class='row'><span class='label'>{{ __('emails.admin.datetime', [], 'en') }}:</span> <span class='value'>{{ \Carbon\Carbon::parse($reservation->reservation_time)->format('d/m/Y H:i') }}</span></div>
        <div class='row'><span class='label'>{{ __('emails.admin.people', [], 'en') }}:</span> <span class='value'>{{ $reservation->party_size }}</span></div>
        <div class='row'><span class='label'>{{ __('emails.admin.phone', [], 'en') }}:</span> <span class='value'>{{ $reservation->customer_phone ?: __('emails.admin.none', [], 'en') }}</span></div>
        <div class='row'><span class='label'>{{ __('emails.admin.email', [], 'en') }}:</span> <span class='value'>{{ $reservation->customer_email ?: __('emails.admin.none', [], 'en') }}</span></div>
        <div class='row'><span class='label'>{{ __('emails.admin.notes', [], 'en') }}:</span> <span class='value'>{{ $reservation->notes ?: __('emails.admin.none', [], 'en') }}</span></div>
    </div>
</body>
</html>
