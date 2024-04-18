<?php

return [
    'access_token' => env('ACCESS_TOKEN', ''),
    'expires_in' => env('EXPIRES_IN', 3600),
    'scope' => env('SCOPE', ''),
    'token_type' => env('TOKEN_TYPE', 'Bearer'),
    'created' => env('CREATED', 0),
    'refresh_token' => env('REFRESH_TOKEN', ''),
    'client_id' => env('CLIENT_ID', ''),
    'client_secret' => env('CLIENT_SECRET', ''),
    'redirect_url' => env('REDIRECT_URL', ''),
];
