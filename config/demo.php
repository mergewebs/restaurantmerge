<?php

return [
    /*
    | Demo data is opt-in in production so a real restaurant database can never
    | be populated merely because the shared demo account exists. An existing
    | local install without an override enables the presentation by default.
    */
    'data_enabled' => (bool) env('DEMO_DATA_ENABLED', env('APP_ENV', 'production') === 'local'),
];
