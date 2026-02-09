<?php

return [
    /*
    |--------------------------------------------------------------------------
    | API Versioning
    |--------------------------------------------------------------------------
    |
    | Define your supported API versions and the default version to use
    | when one isn't specified by the client.
    |
    */

    'versions' => ['v1', 'v2'],

    'default' => 'v1',

    'header' => 'X-API-Version', // or Accept-Version

    /*
    |--------------------------------------------------------------------------
    | Error Codes
    |--------------------------------------------------------------------------
    |
    | Map your application-specific error codes to default messages and status codes.
    |
    */

    'error_codes' => [
        // 'USER_NOT_FOUND' => ['message' => 'The requested user was not found.', 'status' => 404],
    ],

    /*
    |--------------------------------------------------------------------------
    | Deprecations
    |--------------------------------------------------------------------------
    |
    | Register deprecated routes or path patterns to automatically include
    | Sunset and Deprecation headers in the response.
    |
    */

    'deprecations' => [
        // 'route.name' => ['sunset' => '2026-12-31T00:00:00Z', 'link' => 'https://api.example.com/docs/v1', 'message' => 'Shift to v2'],
        // 'api/v1/*'    => ['sunset' => '2026-12-31T00:00:00Z', 'link' => 'https://api.example.com/docs/v1', 'message' => 'Shift to v2'],
    ],
];
