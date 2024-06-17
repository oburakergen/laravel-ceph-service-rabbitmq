<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->header('Authorization');

        if (!$token) {
            return response()->error('Unauthorized', 401);
        }

        try {
            $decoded = JWTAuth::setToken($token)->parseToken()->getPayload();
        } catch (JWTException $e) {
            return response()->error($e->getMessage(), 401);
        }

        $sub = $decoded->get('sub');
        if ($sub) {
            $request->user = $sub;
        } else {
            return response()->error('Unauthorized', 401);
        }

        return $next($request);
    }
}
