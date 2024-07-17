<?php

namespace nguyenanhung\Laravel\BasicAuth\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use nguyenanhung\Laravel\BasicAuth\Helper\Helper;

class BasicAuth
{
    protected array $defaultWhitelist = [
        '127.0.0.1',
    ];
    protected array $defaultEnvUseBasicAuth = [
        'beta',
        'staging',
        'test',
        'develop',
    ];

    /**
     * Prompt for basic authentication credentials.
     *
     * @param Request $request
     * @return Response
     */
    protected function promptForBasicAuthCredentials(Request $request)
    {
        Log::info('BasicAuthCredentials::Failed', [
            'request_ip' => $request->ip() ?? null,
            'request_method' => $request->method() ?? '',
            'request_full_url' => $request->fullUrl() ?? '',
            'request_client_info' => Helper::requestServerInfo(),
            'request_auth' => [
                'PHP_AUTH_USER' => $_SERVER['PHP_AUTH_USER'] ?? null,
                'PHP_AUTH_PW' => $_SERVER['PHP_AUTH_PW'] ?? null,
            ]
        ]);
        return response('Authentication required.', Response::HTTP_UNAUTHORIZED)
            ->header('WWW-Authenticate', 'Basic realm="Restricted Area"');
    }

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $envUseBasicAuth = $this->defaultEnvUseBasicAuth;
        $appENV = config('app.env');
        $isEnabled = config('laravel-basic-auth.enabled');

        // Enable basic authentication for Production environments
        $isEnabledInProduction = config('laravel-basic-auth.in_production');
        if ($isEnabled === true && $isEnabledInProduction === true) {
            $envUseBasicAuth = array_merge(['production'], $envUseBasicAuth);
        }

        // Check if the environment is beta, staging, test or develop
        if (($isEnabled === true) && in_array($appENV, $envUseBasicAuth)) {
            // Define default whitelist IPs
            $defaultWhitelist = $this->defaultWhitelist;

            // Merge default whitelist with whitelist from .env
            $envWhitelist = config('laravel-basic-auth.white_list_ips');
            if (!empty($envWhitelist)) {
                $envWhitelist = explode(',', $envWhitelist);
                $whitelist = array_merge($defaultWhitelist, $envWhitelist);
            } else {
                $whitelist = $defaultWhitelist;
            }

            // Check if the request IP is in the whitelist
            if (in_array($request->ip(), $whitelist)) {
                return $next($request);
            }

            // Prompt for credentials if not provided
            if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW'])) {
                return $this->promptForBasicAuthCredentials($request);
            }

            // Validate credentials
            $username = config('laravel-basic-auth.username');
            $password = config('laravel-basic-auth.password');

            if ($_SERVER['PHP_AUTH_USER'] !== $username || $_SERVER['PHP_AUTH_PW'] !== $password) {
                return $this->promptForBasicAuthCredentials($request);
            }
        }

        return $next($request);
    }
}
