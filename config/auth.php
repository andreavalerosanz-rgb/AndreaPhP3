<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    */

    'defaults' => [
        'guard' => 'web',
        'passwords' => 'travelers', // Actualizado para coincidir con el nombre del provider
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    */

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'travelers', // Apunta al nuevo nombre
        ],
        'admin' => [
            'driver' => 'session',
            'provider' => 'staff', // Apunta al nuevo nombre
        ],
        'corporate' => [
            'driver' => 'session',
            'provider' => 'partners', // Apunta al nuevo nombre
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    */

    'providers' => [
        'staff' => [
            'driver' => 'eloquent',
            'model' => App\Models\StaffMember::class, // Refactor: StaffMember
        ],
        'partners' => [
            'driver' => 'eloquent',
            'model' => App\Models\Partner::class, // Refactor: Partner
        ],
        'travelers' => [
            'driver' => 'eloquent',
            'model' => App\Models\Traveler::class, // Refactor: Traveler
        ],

        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Resetting Passwords
    |--------------------------------------------------------------------------
    */

    'passwords' => [
        'travelers' => [
            'provider' => 'travelers',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
        'staff' => [
            'provider' => 'staff',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
        'partners' => [
            'provider' => 'partners',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Confirmation Timeout
    |--------------------------------------------------------------------------
    */

    'password_timeout' => 10800,

];