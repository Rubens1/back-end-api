<?php

namespace App\Http\Controllers\Pagamento;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;

class CoraController extends Controller
{
    public function token()
    {
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


            curl_close($ch);

            if ($response == false) {
                return response()->json(["message" => "Erro interno"], 400);
            }

            return response()->json($json);

        } catch (\Exception $e) {
            curl_close($ch);
            return response()->json(["message" => $e->getMessage()], 400);
        }

    }



    //Fazer o pagamento pelo pix
    public static function pix(Request $request)
    {
        try {
            $url = "https://matls-clients.api.stage.cora.com.br/invoices";
            $certFile = env('CERTIFICATE');
            $keyFile = env('PRIVATE_KEY');
            $keyPassword = '';



            $response = Http::withOptions([
                'cert' => $certFile,
                'ssl_key' => $keyFile,
                'ssl_key_password' => $keyPassword,
                'headers' => [
                    "Authorization" => "Bearer $request->bearer_token",
                    "Idempotency-Key" => "850dcb7a-90f9-451c-b61d-422d573395d8"
                ]
            ])

                ->post($url, $request->body);

            $json = json_decode($response->body());

            return response()->json($json);

            //return $response->json();
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    //Fazer o pagamento pelo boleto
    public static function boleto(Request $request)
    {
        try {
            $url = "https://matls-clients.api.stage.cora.com.br/invoices";
            $certFile = env('CERTIFICATE');
            $keyFile = env('PRIVATE_KEY');
            $keyPassword = '';

            //dd("Bearer $request->bearer_token");
            $response = Http::withOptions([
                'cert' => $certFile,
                'ssl_key' => $keyFile,
                'ssl_key_password' => $keyPassword,
                'headers' => [
                    "Authorization" => "Bearer $request->bearer_token",
                    "Idempotency-Key" => "dd61d5b9-c9fb-4116-b5e0-1f8436993ac4"
                ]
            ])

                ->post($url, $request->body);

            //dd($response);

            $json = json_decode($response->body());

            return response()->json($json);

            //return $response->json();
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    //Lista de pagamentos
    public static function lista(Request $request)
    {
        $url = "https://matls-clients.api.stage.cora.com.br/token";
    }

}

