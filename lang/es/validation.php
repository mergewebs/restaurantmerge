<?php

return [
    'required' => 'El campo :attribute es obligatorio.',
    'email' => 'El campo :attribute debe ser una dirección de correo válida.',
    'date' => 'El campo :attribute debe ser una fecha válida.',
    'integer' => 'El campo :attribute debe ser un número entero.',
    'min' => [
        'string' => 'El campo :attribute debe tener al menos :min caracteres.',
        'numeric' => 'El campo :attribute debe ser al menos :min.',
        'array' => 'El campo :attribute debe tener al menos :min elementos.',
    ],
    'max' => [
        'string' => 'El campo :attribute no puede tener más de :max caracteres.',
        'numeric' => 'El campo :attribute no puede ser mayor que :max.',
        'array' => 'El campo :attribute no puede tener más de :max elementos.',
    ],
    'string' => 'El campo :attribute debe ser una cadena de texto.',
    'array' => 'El campo :attribute debe ser un arreglo.',
    'attributes' => [
        'customer_name' => 'nombre',
        'customer_phone' => 'teléfono',
        'customer_email' => 'correo electrónico',
        'party_size' => 'número de personas',
        'reservation_time' => 'fecha de reserva',
        'notes' => 'notas',
        'submission_token' => 'token de envío',
        'customerName' => 'nombre',
        'customerPhone' => 'teléfono',
        'locale' => 'idioma',
        'items' => 'productos',
        'password' => 'contraseña',
        'email' => 'correo electrónico',
        'name' => 'nombre',
    ],
];
