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
            $certFile = 'C:\Users\dev03\OneDrive\Documentos\certificate.pem';
            $keyFile = 'C:\Users\dev03\OneDrive\Documentos\private-key.key';
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

            return response()->json($json);

        } catch (\Exception $e) {
            curl_close($ch);
            return response()->json(["message" => "Erro interno"]);
        }

        //return response()->json($json);
    }



    //Fazer o pagamento pelo pix
    public static function pix(Request $request)
    {
        try {
            $url = "https://matls-clients.api.stage.cora.com.br/invoices";
            $certFile = 'C:\Users\dev03\OneDrive\Documentos\certificate.pem';
            $keyFile = 'C:\Users\dev03\OneDrive\Documentos\private-key.key';
            $keyPassword = '';

            $body = [
                "customer" => [
                    "name" => "Rubens",
                    "e-mail" => "rubens@gmail.com",
                    "document" => [
                        "identity" => "06567219521",
                        "type" => "CPF"
                    ],
                    "address" => [
                        "street" => "Rua Gomes de Carvalho",
                        "number" => "1629",
                        "district" => "Vila Olímpia",
                        "city" => "São Paulo",
                        "state" => "SP",
                        "complement" => "N/A",
                        "zip_code" => "00111222"
                    ]
                ],
                "payment_terms" => [
                    "due_date" => "2023-12-12",
                    "fine" => [
                        "amount" => 9000
                    ]
                ],
                "payment_forms" => [
                    "BANK_SLIP"
                ],
                "services" => [
                    [
                        "name" => "Nome do Serviço",
                        "description" => "Aluguel",
                        "amount" => 4000
                    ]
                ]
            ];

            $response = Http::withOptions([
                'cert' => $certFile,
                'ssl_key' => $keyFile,
                'ssl_key_password' => $keyPassword,
                'headers' => [
                    "Authorization" => "Bearer eyJhbGciOiJSUzI1NiIsInR5cCIgOiAiSldUIiwia2lkIiA6ICJremdQdlJXQWNkR2d5NFQxRTk3ajZ4SGF6aEhTWjFraEc1MW4taHZDZGtzIn0.eyJleHAiOjE3MDExMjA5NTksImlhdCI6MTcwMTExNzM1OSwianRpIjoiOWNhZDMyNjYtODcwOS00MjRmLTlmNmItN2NmNzA1YmI4NzZkIiwiaXNzIjoiaHR0cHM6Ly9hdXRoLnN0YWdlLmNvcmEuY29tLmJyL3JlYWxtcy9pbnRlZ3JhdGlvbiIsInN1YiI6Ijc1MzY0ZTg5LWNjYjItNDdjOC1iMTE1LTNkZTE5Zjg2Y2ViNiIsInR5cCI6IkJlYXJlciIsImF6cCI6ImludC1sT0RRd1I3VmdmZkVXZFlqcnhRUnMiLCJhY3IiOiIxIiwic2NvcGUiOiIiLCJjbGllbnRIb3N0IjoiMTc3LjEwMy4xNzYuNzIiLCJjbGllbnRJZCI6ImludC1sT0RRd1I3VmdmZkVXZFlqcnhRUnMiLCJjbGllbnRBZGRyZXNzIjoiMTc3LjEwMy4xNzYuNzIiLCJidXNpbmVzc19pZCI6ImI3N2FkNjQ5LTY0NmEtNDc1OS1hMDc2LWUyZjE3M2NlOTk4ZiIsInBlcnNvbl9pZCI6Ijg1MGRjYjdhLTkwZjktNDUxYy1iNjFkLTQyMmQ1NzMzOTVkOCJ9.oKVIv7kR3TcApo0j79hwiWgNgdnppa3-jytIYegZvfyiqbf8BLY2MzH8G60UJqU6GrKP4imn9b4FmgZpc_qUMvuH5Vge8SITnE7jzjujkWpMxafD0dXVpU5hr6c722rqpdAmidkH3ES9i3oW9GFfDci_8-cTJpqB5iqy1Ob7OplRuEVhesIJbsu1bqwmjvitNRJvGprLzLtI0JUEiLDwDMGbujg7ln1_NIsDRRdMgJZRIdzXiQe-_BWS0Z-1BLpoEcjAing-OEMJ1JKcN63e-q7M9sSYyhN0niKDZyJbP1Z8sBTePJL2K7ExcQVpByDG3UijlrU-NFdaIpZLAXdXYQ",
                    "Idempotency-Key" => "850dcb7a-90f9-451c-b61d-422d573395d8"
                ] 
            ])

                ->post($url, $body);

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
        $url = "https://matls-clients.api.stage.cora.com.br/token";
    }

    //Lista de pagamentos
    public static function lista(Request $request)
    {
        $url = "https://matls-clients.api.stage.cora.com.br/token";
    }

}

