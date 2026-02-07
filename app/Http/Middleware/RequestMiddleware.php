<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequestMiddleware
{
    private string $apiToken = 'e3b0c44298fc1c149afbf4c8996fb92427ae41e4649b934ca495991b7852b855';
    public function handle(Request $request, Closure $next): Response
    {
//        $header = $request->headers->get('Authorization');
//        if ($header === $this->apiToken) {
            return $next($request);
//        }

    }
}
