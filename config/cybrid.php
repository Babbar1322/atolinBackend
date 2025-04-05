<?php

return [
    'env' => env('CYBRID_ENV'),

    'client_id' => env('CYBRID_ENV') === 'sandbox' ? env('SANDBOX_CYBRID_CLIENT_ID') : env('CYBRID_CLIENT_ID'),
    'client_secret' => env('CYBRID_ENV') === 'sandbox' ? env('SANDBOX_CYBRID_CLIENT_SECRET') : env('CYBRID_CLIENT_SECRET'),

    'oauth' => env('CYBRID_ENV') === 'sandbox' ? env('SANDBOX_CYBRID_OAUTH') : env('CYBRID_OAUTH'),

    'api' => env('CYBRID_ENV') === 'sandbox' ? env('SANDBOX_CYBRID_API') : env('CYBRID_API'),

    'bank' => env('CYBRID_ENV') === 'sandbox' ? env('SANDBOX_CYBRID_BANK') : env('CYBRID_BANK'),
    'main_account' => env('CYBRID_ENV') === 'sandbox' ? env('SANDBOX_CYBRID_MAIN_ACCOUNT') : env('CYBRID_MAIN_ACCOUNT'),
];
