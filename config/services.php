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

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'wazuh' => [
        'url' => env('WAZUH_URL', 'https://192.168.56.106:55000'),
        'user' => env('WAZUH_USER', 'wazuh-wui'),
        'pass' => env('WAZUH_PASS', '7EnRDknX3CqKGJsWUo7lyJ?y1GdTmAk?'),
    ],

    'indexer' => [
        'url' => env('INDEXER_URL', 'https://192.168.56.106:9200'),
        'user' => env('INDEXER_USER', 'admin'),
        'pass' => env('INDEXER_PASS', 'BeGt0qZi8IIzpmXgHmz9FLr7p*sxgN6L'),
    ],

];
