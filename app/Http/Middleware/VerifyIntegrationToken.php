<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyIntegrationToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $validToken = config('services.integration.token');
        $token = $request->header('INTEGRATION_TOKEN') ?? $request->query('token');

        if ($token !== $validToken) return response()->json(['error' => 'Unauthorized'], 401);

        return $next($request);
    }
}
