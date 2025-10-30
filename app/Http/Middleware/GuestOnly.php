<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class GuestOnly
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $responseStatus = Response::HTTP_UNAUTHORIZED;
        $responseMessage = __('auth.unauthorized_access');

        abort_if(! $request->expectsJson() && Auth::check(), $responseStatus, $responseMessage);
        abort_if($request->expectsJson() && Auth::guard('sanctum')->check(), $responseStatus, $responseMessage);

        return $next($request);
    }
}
