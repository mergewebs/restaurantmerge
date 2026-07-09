<?php

return [
    'tables' => (bool) env('FEATURE_TABLES_ENABLED', false),
    'kitchen' => (bool) env('FEATURE_KITCHEN_ENABLED', false),
    'takeaway' => (bool) env('FEATURE_TAKEAWAY_ENABLED', false),
];
