<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Closure;
use stdClass;
use Symfony\Component\HttpFoundation\Response;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Authenticate 
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request)
    {
        return $request->expectsJson() ? null : response()->with([
            "message" => "N"
        ]);
    }

    public function handle(Request $request, Closure $next): Response
    {
        
        if(!$request->header("authorization") || $request->header("authorization") == null) {
            return response()->json([
                "status" => "error",
                "message" => "Não autorizado"
            ]);
        }

        return $next($request);
    }
}
