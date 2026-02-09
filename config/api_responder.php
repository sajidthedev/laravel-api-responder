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
];
