<?php

return [
    'name' => env('RESTAURANT_NAME', 'My Restaurant'),
    'contact_email' => env('RESTAURANT_CONTACT_EMAIL', env('MAIL_FROM_ADDRESS', 'hello@example.com')),
    'contact_form_recipient' => env('RESTAURANT_CONTACT_FORM_RECIPIENT', 'mergewebs@gmail.com'),
    'contact_phone' => env('RESTAURANT_CONTACT_PHONE', ''),
    'whatsapp' => env('RESTAURANT_WHATSAPP', ''),
    'address' => env('RESTAURANT_ADDRESS', ''),
    'website' => env('RESTAURANT_WEBSITE', ''),
];
