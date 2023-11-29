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
                    "Authorization" => "Bearer eyJhbGciOiJSUzI1NiIsInR5cCIgOiAiSldUIiwia2lkIiA6ICJremdQdlJXQWNkR2d5NFQxRTk3ajZ4SGF6aEhTWjFraEc1MW4taHZDZGtzIn0.eyJleHAiOjE3MDExNzgyNjAsImlhdCI6MTcwMTE3NDY2MCwianRpIjoiOTliZmM4N2QtNmQ3OS00MTIzLThlNWUtNTVhZDQzZjgzMjQ4IiwiaXNzIjoiaHR0cHM6Ly9hdXRoLnN0YWdlLmNvcmEuY29tLmJyL3JlYWxtcy9pbnRlZ3JhdGlvbiIsInN1YiI6Ijc1MzY0ZTg5LWNjYjItNDdjOC1iMTE1LTNkZTE5Zjg2Y2ViNiIsInR5cCI6IkJlYXJlciIsImF6cCI6ImludC1sT0RRd1I3VmdmZkVXZFlqcnhRUnMiLCJhY3IiOiIxIiwic2NvcGUiOiIiLCJjbGllbnRIb3N0IjoiNDUuMjM4LjQyLjE5OCIsImNsaWVudElkIjoiaW50LWxPRFF3UjdWZ2ZmRVdkWWpyeFFScyIsImNsaWVudEFkZHJlc3MiOiI0NS4yMzguNDIuMTk4IiwiYnVzaW5lc3NfaWQiOiJiNzdhZDY0OS02NDZhLTQ3NTktYTA3Ni1lMmYxNzNjZTk5OGYiLCJwZXJzb25faWQiOiI4NTBkY2I3YS05MGY5LTQ1MWMtYjYxZC00MjJkNTczMzk1ZDgifQ.vk1EpRI__gCU8I578DdwFxqniu9jb5mxO3HgD8O2h-97UQ8TLx6JSdxnOnmNlqE6szCEkJUZav2rU9rChpDx4E17u-g6hlOeVaF_QuZNcFKeqSNhLr6Loq5tj3T1ypGqGI8ElpaIIX901Tug5qhYtlyQH_tEilsf6d5iyn-yxjiuSyiKITjcoa8yiHkJDQw9S1dMbRVw2GCCLdB7jUdS-DOZtXElgZsJteGDK0iVYd2sZCN_CqLB2i-zDYabRzplVxxxEo5HjJxtMbI8XNTDJkNHFCP5AIU8qj7Ha1yfOf75KoQzNC5TajRPjQDPzJef7Dpj3h6ZphoeeIDwva8dSw",
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

