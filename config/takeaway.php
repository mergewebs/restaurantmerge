<?php

return [
    'brand' => [
        'restaurant_name' => env('TAKEAWAY_RESTAURANT_NAME', env('APP_NAME', 'Restaurant')),
        'logo_url' => env('TAKEAWAY_LOGO_URL', 'https://koyabcn.es/wp-content/uploads/2025/07/koya-logo-e1744122653245.jpg'),
    ],

    'notification' => [
        'email_enabled' => (bool) env('TAKEAWAY_NOTIFY_EMAIL', true),
        'email_recipients' => env('TAKEAWAY_NOTIFY_EMAILS', env('ADMIN_EMAIL', '')),
    ],
];
