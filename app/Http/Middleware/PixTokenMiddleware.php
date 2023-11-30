<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PixTokenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        //$request->session()->forget("pay_token");

         if (!$request->session()->has("pay_token")) {
            try {
                $url = 'https://matls-clients.api.stage.cora.com.br/token';
                $certFile = env('CERTIFICATE');
                $keyFile = env('PRIVATE_KEY');
                $keyPassword = ''; // If your private key is password-protected

                $data = [
                    'grant_type' => 'client_credentials',
                    'client_id' => 'int-lODQwR7VgffEWdYjrxQRs',
                ];

                $ch = curl_init();

                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSLCERT, $certFile);
                curl_setopt($ch, CURLOPT_SSLKEY, $keyFile);
                curl_setopt($ch, CURLOPT_SSLKEYPASSWD, $keyPassword);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Set to true for production, false for testing
                curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

                $response = curl_exec($ch);

                $json = json_decode($response);

                $request->session()->put("pay_token", $json->access_token);

                curl_close($ch);

        
                if ($response == false) {
                    return response()->json(["message" => "Erro interno"], 400);
                }

                return $next($request);

            } catch (\Exception $e) {
                curl_close($ch);
                return response()->json(["message" => $e->getMessage()], 400);
            } 
        }

        return $next($request);

        
    }
}
