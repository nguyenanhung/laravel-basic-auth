<?php
if (!function_exists('is_production')) {
    function is_production(): bool
    {
        return config('app.env') === 'production';
    }
}
if (!function_exists('is_beta')) {
    function is_beta(): bool
    {
        return config('app.env') === 'beta';
    }
}
if (!function_exists('is_staging')) {
    function is_staging(): bool
    {
        return config('app.env') === 'staging';
    }
}
if (!function_exists('is_local')) {
    function is_local(): bool
    {
        return config('app.env') === 'local';
    }
}
