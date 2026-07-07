<?php

return [
    'required' => 'The :attribute field is required.',
    'email' => 'The :attribute field must be a valid email address.',
    'date' => 'The :attribute field must be a valid date.',
    'integer' => 'The :attribute field must be an integer.',
    'min' => [
        'string' => 'The :attribute field must be at least :min characters.',
        'numeric' => 'The :attribute field must be at least :min.',
        'array' => 'The :attribute field must have at least :min items.',
    ],
    'max' => [
        'string' => 'The :attribute field may not be greater than :max characters.',
        'numeric' => 'The :attribute field may not be greater than :max.',
        'array' => 'The :attribute field may not have more than :max items.',
    ],
    'string' => 'The :attribute field must be a string.',
    'array' => 'The :attribute field must be an array.',
    'attributes' => [
        'customer_name' => 'name',
        'customer_phone' => 'phone',
        'customer_email' => 'email',
        'party_size' => 'party size',
        'reservation_time' => 'reservation date',
        'notes' => 'notes',
        'submission_token' => 'submission token',
        'customerName' => 'name',
        'customerPhone' => 'phone',
        'locale' => 'language',
        'items' => 'items',
        'password' => 'password',
        'email' => 'email',
        'name' => 'name',
    ],
];
