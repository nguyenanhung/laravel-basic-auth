<?php

namespace nguyenanhung\Laravel\BasicAuth\Helper;

class Helper
{
    public static function requestServerInfo(): array
    {
        return [
            'remote_address' => $_SERVER['REMOTE_ADDR'] ?? 'Unknown',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown',
            'server_name' => $_SERVER['SERVER_NAME'] ?? 'Unknown',
            'request_method' => $_SERVER['REQUEST_METHOD'] ?? 'Unknown',
            'request_uri' => $_SERVER['REQUEST_URI'] ?? 'Unknown',
            'server_protocol' => $_SERVER['SERVER_PROTOCOL'] ?? 'Unknown',
            'query_string' => $_SERVER['QUERY_STRING'] ?? 'None',
            'time' => date('Y-m-d H:i:s'),
        ];
    }
}
