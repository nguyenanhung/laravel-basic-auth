<?php

return [
    'enabled' => env('ENABLED_BASIC_AUTH', false), // Default is false
    'log_in_production' => env('LOG_ONLY_PRODUCTION', false), // Default is false
    'in_production' => env('ENABLED_BASIC_AUTH_IN_PRODUCTION', false), // Default is false
    'username' => env('BASIC_AUTH_USERNAME', 'hungna'),
    'password' => env('BASIC_AUTH_PASSWORD', 'HungNA@Password@2024'),
    'white_list_ips' => env('WHITELIST_IPS', ['127.0.0.1']),
];
