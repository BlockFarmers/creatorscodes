<?php

return [
    'paypal' => [
        'mode' => env('CREATORSCODES_PAYPAL_MODE', 'sandbox'),
        'client_id' => env('CREATORSCODES_PAYPAL_CLIENT_ID'),
        'client_secret' => env('CREATORSCODES_PAYPAL_CLIENT_SECRET'),
    ],
];
