<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    |
    | This option defines the default authentication "guard" and password
    | reset "broker" for your application. We will keep it as 'web' (sessions).
    |
    */

    'defaults' => [
        'guard' => env('AUTH_GUARD', 'web'),
        'passwords' => env('AUTH_PASSWORD_BROKER', 'users'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    |
    | Supported: "session", "token", "firebase" (via third-party drivers)
    |
    */

    'guards' => [
        // Standard Session Guard (Perfect for your login modal redirect)
        // Your controller verifies the Firebase ID token and starts a secure,
        // traditional PHP cookie session using Auth::login().
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        // OPTIONAL: Stateless API Guard
        // Enable this if you want Laravel routes to verify the Firebase JWT
        // Bearer Token on every stateless HTTP request (e.g., using a custom driver).
        'api' => [
            'driver' => 'firebase',
            'provider' => 'users',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    |
    | Tells Laravel how to retrieve user records out of your database.
    | We use Eloquent to sync Firebase users with local database records.
    |
    */

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => env('AUTH_MODEL', App\Models\User::class),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Resetting Passwords
    |--------------------------------------------------------------------------
    |
    | Because Firebase Auth stores and secures credentials externally,
    | password resets will be handled directly through Firebase on the client,
    | bypasssing these standard Laravel mail tables.
    |
    */

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Confirmation Timeout
    |--------------------------------------------------------------------------
    |
    */

    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800),

];
