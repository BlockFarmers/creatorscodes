<?php

return [
    'paypal' => [
        // 'sandbox' pour les tests, 'live' pour le vrai argent
        'mode' => env('CREATORCODES_PAYPAL_MODE', 'sandbox'),
        'client_id' => env('CREATORCODES_PAYPAL_CLIENT_ID'),
        'client_secret' => env('CREATORCODES_PAYPAL_CLIENT_SECRET'),
    ],
];
