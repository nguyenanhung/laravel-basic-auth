<?php

namespace nguyenanhung\Laravel\BasicAuth\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use nguyenanhung\Laravel\BasicAuth\Helper\Helper;
use Symfony\Component\HttpFoundation\Response;

class BasicAuthWithIP
{
    use AcceptRestfulTrait, SupportTrait;

    protected $defaultWhitelist = [
        '127.0.0.1',
    ];

    public function handle(Request $request, Closure $next)
    {
        $origin = $request->header('Origin');
        $allowed = false;

        // Define default whitelist IPs
        $defaultWhitelist = $this->defaultWhitelist;

        // Merge default whitelist with whitelist from .env
        $envWhitelist = config('laravel-basic-whitelist-ip.white_list_ips');
        if (!empty($envWhitelist)) {
            $envWhitelist = explode(',', $envWhitelist);
            $whitelist = array_merge($defaultWhitelist, $envWhitelist);
        } else {
            $whitelist = $defaultWhitelist;
        }
        $clientIP = $request->ip();
        if (in_array($clientIP, $whitelist)) {
            $allowed = true;
        }

        if ($allowed) {
            return $this->acceptRequestRestful($origin, $request, $next);
        }
        if ($this->checkWriteLog() === true) {
            Log::info('Laravel::BasicAuthWithIP::Failed', [
                'request_origin' => $origin,
                'request_ip' => $request->ip() ?? null,
                'request_method' => $request->method() ?? '',
                'request_full_url' => $request->fullUrl() ?? '',
                'request_client_info' => Helper::requestServerInfo()
            ]);
        }
        return response()->json(
            [
                'message' => 'Unauthenticated'
            ],
            Response::HTTP_UNAUTHORIZED
        );
    }
}
