<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class LogRequest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        Log::info('Request URL: ' . $request->fullUrl());
        Log::info('Request Method: ' . $request->method());
        Log::info('Request Headers: ' . json_encode($request->headers->all()));
        Log::info('Request Body: ' . json_encode($request->all()));

        return $next($request);
    }
}
