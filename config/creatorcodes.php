<?php

return [
    'paypal' => [
        'mode' => env('CREATORCODES_PAYPAL_MODE', 'sandbox'),
        'client_id' => env('CREATORCODES_PAYPAL_CLIENT_ID'),
        'client_secret' => env('CREATORCODES_PAYPAL_CLIENT_SECRET'),
    ],
];
