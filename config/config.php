<?php

return [
    'enabled' => env('ENABLED_BASIC_AUTH', true), // Default is true
    'in_production' => env('ENABLED_BASIC_AUTH_IN_PRODUCTION', false), // Default is false
    'username' => env('BASIC_AUTH_USERNAME', 'hungna'),
    'password' => env('BASIC_AUTH_PASSWORD', 'HungNA@Password@2024'),
    'white_list_ips' => env('WHITELIST_IPS', ''),
];
