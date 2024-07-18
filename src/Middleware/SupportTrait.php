<?php

namespace nguyenanhung\Laravel\BasicAuth\Middleware;

trait SupportTrait
{
    protected function checkWriteLog(): bool
    {
        $logInProduction = config('laravel-basic-auth.log_in_production');
        if (is_production() === false && $logInProduction === true) {
            return false;
        }
        return true;
    }
}
