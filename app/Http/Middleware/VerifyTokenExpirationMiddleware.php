<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;


class VerifyTokenExpirationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {

            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }

            return $next($request);
        
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
        
            return response()->json(['token_expired'], $e->getMessage());
        
        } catch(\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json([
                "invalid_token" => "Token invalido"
            ]);
        }
    }
}
