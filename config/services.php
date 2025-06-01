<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'google' => [
        'client_id' => env('BOXLEO_EMAIL_CLIENT_ID'),
        'client_secret' => env('BOXLEO_EMAIL_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT_URI'),
    ],
    

    'paypal' => [
        'id' => env('PAYPAL_ID', 'AdrdXAB7OLpZJXDBnggg46GjYLBWd9zP7S-PXYXbUEGRZTmDPONyZHh7xNwVtuks2SPEvlkvst59D03f'),
        'secret' => env('PAYPAL_SECRET', 'EFAOEdJRuOIrE3HcLK2__1MBFCu7_WY-NgsBy7EU7t7aedDW4SVqjzLPyfPJXNzU_gR7FUKN6zO9tb_u'),
        'url' => [
            'redirect' => 'pos.jim/execute-payment',
            'cancel'=>'pos.jim/cancel',
            'executeAgreement' => [
                'success'=>'http://pos.jim/execute-agreement/true',
                'failure'=>'http://pos.jim/execute-agreement/false'
            ]
        ]
    ],

];
