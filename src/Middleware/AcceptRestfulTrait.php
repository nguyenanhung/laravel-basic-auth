<?php

namespace nguyenanhung\Laravel\BasicAuth\Middleware;

use Closure;
use Illuminate\Http\Request;

trait AcceptRestfulTrait
{
    protected function acceptRequestRestful($origin, Request $request, Closure $next)
    {
        return $next($request)
            ->header('Access - Control - Allow - Origin', $origin)
            ->header('Access - Control - Allow - Methods', 'GET, POST, PUT, DELETE, OPTIONS')
            ->header('Access - Control - Allow - Headers', 'Content - Type, Authorization');
    }
}
