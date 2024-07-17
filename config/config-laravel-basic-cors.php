<?php

return [
    'enabled_list_ips' => env('ENABLED_WHITELIST_IPS', false), // Default is false
    'white_list_ips' => env('WHITELIST_IPS', ['127.0.0.1']),
    'accept_from_url' => env('APP_CORS_ACCEPT_FROM'),
];
