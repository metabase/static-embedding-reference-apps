<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Resend, Postmark, AWS, and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    /*
    | Metabase guest embeds
    | https://www.metabase.com/docs/latest/embedding/guest-embedding
    */

    'metabase' => [
        // The URL people's browsers use to reach your Metabase.
        'site_url' => env('METABASE_SITE_URL', 'http://localhost:3000'),

        // The embedding secret key, from Embedding hub > Security in your
        // Metabase. The default is the key of the demo Metabase in this
        // repository. Keep your own key in .env and out of version control.
        'secret_key' => env('METABASE_SECRET_KEY', 'a1c0952f3ff361f1e7dd8433a0a50689a004317a198ecb0a67ba90c73c27a958'),

        // The dashboard to embed. In the demo Metabase, dashboard 3 is
        // "Customer overview", published as a guest embed with its Customer
        // ID filter locked.
        'dashboard_id' => (int) env('METABASE_DASHBOARD_ID', 3),
    ],

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

];
