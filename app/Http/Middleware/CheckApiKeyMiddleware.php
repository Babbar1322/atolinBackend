<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckApiKeyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$request->hasHeader('X-Atolin-App-Key')) {
            return response()->json('Unauthorised', 401);
        } else if ($request->header('X-Atolin-App-Key') !== env('API_KEY')) {
            return response()->json('Unauthorised', 401);
        }
        return $next($request);
    }
}
