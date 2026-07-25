<?php

return [
    'tables' => (bool) env('FEATURE_TABLES_ENABLED', true),
    'kitchen' => (bool) env('FEATURE_KITCHEN_ENABLED', false),
];
