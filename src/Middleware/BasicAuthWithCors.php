<?php

namespace nguyenanhung\Laravel\BasicAuth\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use nguyenanhung\Laravel\BasicAuth\Helper\Helper;
use Symfony\Component\HttpFoundation\Response;

class BasicAuthWithCors
{
    use AcceptRestfulTrait, SupportTrait;

    protected $defaultWhitelist = [
        '127.0.0.1',
    ];
    protected $addDomainPassCors = [];
    protected $defaultDomainPassCors = [
        'localhost.test'
    ];
    protected $defaultDomain = 'localhost.test';
    protected $overrideErrorResponse = false;

    protected function patternDomain($domain = ''): string
    {
        if (empty($domain)) {
            $domain = $this->defaultDomain;
        }
        $domain = str_replace('.', '\.', $domain);
        $domain = trim($domain);
        return '/^(https?:\/\/)?([a-zA-Z0-9-]+\.)*' . $domain . '\/?/';
    }

    protected function errorResponse(Request $request, $method = '')
    {
        $response = [
            'error' => 'Cors Error',
            'origin' => $request->header('Origin') ?? null,
            'method' => $method
        ];
        return response()->json($response, Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $origin = $request->header('Origin');
        $defaultListDomainCors = $this->defaultDomainPassCors;

        // Accept with Whitelist IPS
        $enabledWhitelistIP = config('laravel-basic-cors.enabled_list_ips');
        if ($enabledWhitelistIP === true) {
            $defaultWhitelist = $this->defaultWhitelist;
            $allowedIP = false;

            // Merge default whitelist with whitelist from .env
            $envWhitelist = config('laravel-basic-cors.white_list_ips');
            if (!empty($envWhitelist)) {
                $envWhitelist = explode(',', $envWhitelist);
                $whitelist = array_merge($defaultWhitelist, $envWhitelist);
            } else {
                $whitelist = $defaultWhitelist;
            }
            $clientIP = $request->ip();
            if (in_array($clientIP, $whitelist)) {
                $allowedIP = true;
            }
            if ($allowedIP === true) {
                return $this->acceptRequestRestful($origin, $request, $next);
            }
        }

        // Add from Config
        $acceptCorsUrl = config('laravel-basic-cors.accept_from_url');
        if (!empty($acceptCorsUrl)) {
            $acceptListDomainCors = array_merge_recursive($defaultListDomainCors, explode(',', $acceptCorsUrl));
            $acceptListDomainCors = array_unique($acceptListDomainCors);
        } else {
            $acceptListDomainCors = $defaultListDomainCors;
        }

        if (!empty($this->addDomainPassCors) && is_array($this->addDomainPassCors)) {
            $listDomainCors = array_merge_recursive($acceptListDomainCors, $this->addDomainPassCors);
        } else {
            $listDomainCors = $acceptListDomainCors;
        }
        $listDomainCors = array_unique($listDomainCors);

        foreach ($listDomainCors as $domain) {
            if ($origin && preg_match($this->patternDomain($domain), $origin)) {
                return $this->acceptRequestRestful($origin, $request, $next);
            }
        }
        if ($this->checkWriteLog() === true) {
            Log::info('Laravel::BasicAuthWithCors::Failed', [
                'request_origin' => $origin,
                'accept_origin' => $listDomainCors,
                'request_ip' => $request->ip() ?? null,
                'request_method' => $request->method() ?? '',
                'request_full_url' => $request->fullUrl() ?? '',
                'request_client_info' => Helper::requestServerInfo()
            ]);
        }
        return $this->errorResponse($request, 'BasicAuthWithCors::handle');
    }
}
