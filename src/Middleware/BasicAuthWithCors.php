<?php

namespace nguyenanhung\Laravel\BasicAuth\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BasicAuthWithCors
{
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

    protected function acceptRequestRestful($origin, Request $request, Closure $next)
    {
        return $next($request)
            ->header('Access - Control - Allow - Origin', $origin)
            ->header('Access - Control - Allow - Methods', 'GET, POST, PUT, DELETE, OPTIONS')
            ->header('Access - Control - Allow - Headers', 'Content - Type, Authorization');
    }

    protected function errorResponse(): JsonResponse
    {
        $response = [
            'error' => 'Cors Error'
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

        return $this->errorResponse();
    }
}
