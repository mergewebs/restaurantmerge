<?php

return [
    'brand' => [
        'restaurant_name' => env('TAKEAWAY_RESTAURANT_NAME', env('APP_NAME', 'Restaurant')),
        'logo_url' => env('TAKEAWAY_LOGO_URL', 'https://grandrestaurantv7-0.b-cdn.net/bistro/wp-content/uploads/sites/3/2024/08/logo-white.png'),
    ],

    'notification' => [
        'email_enabled' => (bool) env('TAKEAWAY_NOTIFY_EMAIL', true),
        'email_recipients' => env('TAKEAWAY_NOTIFY_EMAILS', env('ADMIN_EMAIL', '')),
    ],
];
