<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Public API Clients
    |--------------------------------------------------------------------------
    | These are the allowed "public" clients (WordPress, mobile app, etc.)
    | that can access signed endpoints.
    |
    | Example: LIMOFLUX_PUBLIC_CLIENTS=wordpress,mobile-web
    */
    'public_clients' => array_values(array_filter(array_map(
        'trim',
        explode(',', env('LIMOFLUX_PUBLIC_CLIENTS', 'wordpress'))
    ))),

    /*
    |--------------------------------------------------------------------------
    | Public API Secret
    |--------------------------------------------------------------------------
    | Shared secret used for HMAC signing. Must be long and random.
    | Store in Laravel .env and WordPress wp-config.php / env.
    */
    'public_api_secret' => env('LIMOFLUX_PUBLIC_API_SECRET'),

    /*
    |--------------------------------------------------------------------------
    | Signature Settings
    |--------------------------------------------------------------------------
    */
    'signature' => [
        // Allowed timestamp drift in seconds (default: 5 minutes)
        'max_clock_skew_seconds' => (int) env('LIMOFLUX_PUBLIC_MAX_SKEW', 300),

        // If true, we require X-Nonce and prevent replay using cache.
        'require_nonce' => (bool) env('LIMOFLUX_PUBLIC_REQUIRE_NONCE', false),

        // Cache TTL for nonce replay protection (seconds)
        'nonce_ttl_seconds' => (int) env('LIMOFLUX_PUBLIC_NONCE_TTL', 300),
    ],

];