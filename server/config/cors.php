<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Laravel CORS
    |--------------------------------------------------------------------------
    |
    | allowedOrigins, allowedHeaders and allowedMethods can be set to array('*')
    | to accept any value.
    |
    */

    'supportsCredentials' => true,
    'allowedOrigins' => [env('CLIENTE_URL', '*')],
    'allowedOriginsPatterns' => [],
    'allowedHeaders' => ['Accept', 'Authorization', 'Content-Type', 'Origin'],
    'allowedMethods' => ['OPTIONS', 'GET', 'POST', 'PUT', 'DELETE'],
    'exposedHeaders' => [],
    'maxAge' => 60 * 60 * 24, // 1 día

];
