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

    'noiseaware' => [
        'base_api_url' => env("NOISEAWARE_API_BASE_URL"),
        'oauth_url' => env("NOISEAWARE_OAUTH_URL"),
        'client_id' => env("NOISEAWARE_CLIENT_ID", 'Futurestay'),
        'client_secret' => env("NOISEAWARE_CLIENT_SECRET", '97623tMzwJUulBK0'),
        'partner_id' => env("NOISEAWARE_PARTNER_ID", 1),
        'booking_suite_key' => env("BOOKING_SUITE_KEY"),
    ],

    'mandrill' => [
        'key' => env('MANDRILL_API_KEY'),
        'template' => env('MANDRILL_EMAIL_TEMPLATE'),
    ],
    'twilio' => [
        'sid' => env('TWILIO_SID'),
        'token' => env('TWILIO_TOKEN'),
        'phone_number' => env('TWILIO_PHONE_NUMBER'),
    ],
    'google' => [
        'timezone' => [
            'api_url' => env('GOOGLE_TIMEZONE_API_URL'),
            'api_key' => env('GOOGLE_TIMEZONE_API_KEY'),
        ],
        'distancematrix' => [
            'api_url' => env('GOOGLE_DISTANCEMATRIX_API_URL'),
            'api_key' => env('GOOGLE_DISTANCEMATRIX_API_KEY'),
        ]
    ],

];
