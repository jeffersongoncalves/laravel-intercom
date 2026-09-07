<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Intercom API Key
    |--------------------------------------------------------------------------
    |
    | Your Intercom access token. Find it under Settings > Developers >
    | your app in your Intercom workspace.
    |
    */
    'api_key' => env('INTERCOM_API_KEY', ''),

    /*
    |--------------------------------------------------------------------------
    | Intercom API Version
    |--------------------------------------------------------------------------
    |
    | Sent as the "Intercom-Version" header on every request.
    | See https://developers.intercom.com/docs/references/rest-api/versioning
    |
    */
    'api_version' => env('INTERCOM_API_VERSION', '2.11'),

    /*
    |--------------------------------------------------------------------------
    | Default Pagination Size
    |--------------------------------------------------------------------------
    |
    | Used as the default "per_page" for list endpoints when none is given.
    |
    */
    'default_per_page' => env('INTERCOM_DEFAULT_PER_PAGE', 15),

];
