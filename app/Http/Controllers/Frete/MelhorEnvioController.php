<?php

namespace App\Http\Controllers\Frete;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Exception;

class MelhorEnvioController extends Controller
{
    //Cotação do produto
    public function cotacao(Request $request){
        try {
            $url = env("MELHOR_ENVIO").'shipment/calculate';

            $body = [
                "from" => [
                    "postal_code" => $request->from
                ],
                "to" => [
                    "postal_code"=> $request->to
                ],
                  "package" => [
                    "height"=> $request->height,
                    "width"=> $request->width,
                    "length"=> $request->length,
                    "weight"=> $request->weight
                  ]
            ];
              
              $response = Http::withoutVerifying()->withOptions([
                'headers' => [
                    "Authorization" => "Bearer ".env("TOKEN_MELHOR_ENVIO"),
                ]
            ])->post($url, $body);

            
              $data = $response->json();
              return response()->json(['data' => $data], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
       
    }

    //Carrinho
    public function carrinho(Request $request){
        try {
            $url = env("MELHOR_ENVIO").'cart';
            $array = [];
            foreach ($request as $key => $value) {
                $array[$key] = $value;
                $produtos = ["products" => $array];
            }
            $body = [
                "service" => $request->service,
                "agency " => $request->agency,
                "from" => [
                    "name" => $request->from_name,
                    "address" => $request->from_address,
                    "city" => $request->from_city,
                    "postal_code" => $request->from_postal_code,
                    "phone" => $request->from_phone,
                    "company_document" => $request->cnpj,
                ],
                "to" => [
                    "name" => $request->to_name ,
                    "address" => $request->to_address,
                    "city" => $request->to_city,
                    "postal_code" => $request->to_postal_code,
                    "phone" => $request->to_phone,
                    "document" => $request->cpf,
                ],
                "packages" => [
                    [
                        "price" => $request->price,
                        "discount" => $request->discount,
                        "format"=> $request->format,
                        "dimensions" => [
                            "height" => $request->height,
                            "width" => $request->width,
                            "length" => $request->length
                        ],
                        "weight" => $request->weight,
                        "insurance_value" => $request->insurance_value,
                        $produtos
                    ]
                ],
                "volumes" => [
                    "height" => $request->volumes_height,
                    "width" => $request->volumes_width,
                    "length" => $request->volumes_length,
                    "weight" => $request->volumes_weight
                ],
                "options" => [
                    "insurance_value" => $request->options_value,
                    "receipt" => true,
                    "own_hand" => true,
                    "reverse" => true,
                    "non_commercial" => true
                ]
            ];

            $response = Http::withoutVerifying()->withOptions([
                'headers' => [
                    "Authorization" => "Bearer ".env("TOKEN_MELHOR_ENVIO"),
                    "User-Agent" => "rubens.jesus1997@gmail.com",
                    "Content-Type" =>  "application/json",
                    "Accept" => "application/json"
                    ]
            ])->post($url, $body);

            $data = $response->json();
            return response()->json(['data' => $data], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    //Paga o frete do pedido
    public function pagaFrete(Request $request){

        try{
            $url = env("MELHOR_ENVIO").'shipment/checkout';

            $array = [];

            foreach ($request->orders as $key => $value) {
                $array[$key] = $value;
                
                $body = [
                    "orders" => $array,
                ];
            }
            $response = Http::withoutVerifying()->withOptions([
                'headers' => [
                    "Authorization" => "Bearer ".env("TOKEN_MELHOR_ENVIO"),
                    "User-Agent" => "rubens.jesus1997@gmail.com",
                    "Content-Type" =>  "application/json",
                    "Accept" => "application/json"
                    ]
            ])->post($url, $body);

            $data = $response->json();
            return response()->json(['data' => $data], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    //Gera a etiqueta
    public function geraEtiqueta(Request $request){
        try{
            $array = [];

            foreach ($request->orders as $key => $value) {
                $array[$key] = $value;
            
                $body = [
                    "orders" => $array,
                ];
            }
            $url = env("MELHOR_ENVIO").'shipment/generate';
            $response = Http::withoutVerifying()->withOptions([
                'headers' => [
                    "Authorization" => "Bearer ".env("TOKEN_MELHOR_ENVIO"),
                    "User-Agent" => "rubens.jesus1997@gmail.com",
                    "Content-Type" =>  "application/json",
                    "Accept" => "application/json"
                    ]
            ])->post($url, $body);

            $data = $response->json();
            return response()->json(['data' => $data], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    //Imprimir a etiqueta
    public function imprimirEtiqueta(Request $request){
        try{
            $array = [];

            foreach ($request->orders as $key => $value) {
                $array[$key] = $value;
            
                $body = [
                    "orders" => $array,
                ];
            }
            $url = env("MELHOR_ENVIO").'shipment/print';
            $response = Http::withoutVerifying()->withOptions([
                'headers' => [
                    "Authorization" => "Bearer ".env("TOKEN_MELHOR_ENVIO"),
                    "User-Agent" => "rubens.jesus1997@gmail.com",
                    "Content-Type" =>  "application/json",
                    "Accept" => "application/json"
                    ]
            ])->post($url, $body);

            $data = $response->json();
            return response()->json(['data' => $data], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    //Lista de pedidos no carrinho
    public function listaCarrinho(){
        try {
            $url = env("MELHOR_ENVIO").'cart';
            $response = Http::withoutVerifying()->withOptions([
                'headers' => [
                    "Authorization" => "Bearer ".env("TOKEN_MELHOR_ENVIO"),
                    "User-Agent" => "rubens.jesus1997@gmail.com",
                    "Content-Type" =>  "application/json",
                    "Accept" => "application/json"
                    ]
            ])->get($url);
            $data = $response->json();
            return response()->json(['data' => $data], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    //Lista de etiquetas
    public function listaEtiqueta(){
        try {
            $url = env("MELHOR_ENVIO").'orders';
            $response = Http::withoutVerifying()->withOptions([
                'headers' => [
                    "Authorization" => "Bearer ".env("TOKEN_MELHOR_ENVIO"),
                    "User-Agent" => "rubens.jesus1997@gmail.com",
                    "Content-Type" =>  "application/json",
                    "Accept" => "application/json"
                    ]
            ])->get($url);
            $data = $response->json();
            return response()->json(['data' => $data], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    //Informação de uma etiqueta
    public function infoEtiqueta($id){
        try {
            $url = env("MELHOR_ENVIO").'orders/'.$id;
            $response = Http::withoutVerifying()->withOptions([
                'headers' => [
                    "Authorization" => "Bearer ".env("TOKEN_MELHOR_ENVIO"),
                    "User-Agent" => "rubens.jesus1997@gmail.com",
                    "Content-Type" =>  "application/json",
                    "Accept" => "application/json"
                    ]
            ])->get($url);
            $data = $response->json();
            return response()->json(['data' => $data], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    //Pesquisa etiqueta
    public function pesquisaEtiqueta(Request $request){
        try {
            $url = env("MELHOR_ENVIO").'orders/search';
            $body = [
                "q" => $request->q
            ];
            $response = Http::withoutVerifying()->withOptions([
                'headers' => [
                    "Authorization" => "Bearer ".env("TOKEN_MELHOR_ENVIO"),
                    "User-Agent" => "rubens.jesus1997@gmail.com",
                    "Content-Type" =>  "application/json",
                    "Accept" => "application/json"
                    ]
            ])->get($url, $body);
            $data = $response->json();
            return response()->json(['data' => $data], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    //Verificar se etiqueta pode ser cancelada
    public function verificaEtiqueta(Request $request){
        try {
            $url = env("MELHOR_ENVIO").'shipment/cancellable';
            $array = [];

            foreach ($request->orders as $key => $value) {
                $array[$key] = $value;
            
                $body = [
                    "orders" => $array,
                ];
            }
            $response = Http::withoutVerifying()->withOptions([
                'headers' => [
                    "Authorization" => "Bearer ".env("TOKEN_MELHOR_ENVIO"),
                    "User-Agent" => "rubens.jesus1997@gmail.com",
                    "Content-Type" =>  "application/json",
                    "Accept" => "application/json"
                    ]
            ])->post($url, $body);
            $data = $response->json();
            return response()->json(['data' => $data], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    //Cancelamento de etiqueta
    public function cancelaEtiqueta(Request $request){
        try {
            $url = env("MELHOR_ENVIO").'shipment/cancel';
            $array = [];

            foreach ($request->orders as $key => $value) {
                $array[$key] = $value;
            
                $body = [
                    "orders" => $array,
                    "reason_id" => 2
                ];
            }
            $response = Http::withoutVerifying()->withOptions([
                'headers' => [
                    "Authorization" => "Bearer ".env("TOKEN_MELHOR_ENVIO"),
                    "User-Agent" => "rubens.jesus1997@gmail.com",
                    "Content-Type" =>  "application/json",
                    "Accept" => "application/json"
                    ]
            ])->post($url, $body);
            $data = $response->json();
            return response()->json(['data' => $data], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    //Rastreia o envio
    public function rastreio(Request $request){
        try {
            $url = env("MELHOR_ENVIO").'shipment/tracking';
            $array = [];

            foreach ($request->orders as $key => $value) {
                $array[$key] = $value;
            
                $body = [
                    "orders" => $array
                ];
            }
            $response = Http::withoutVerifying()->withOptions([
                'headers' => [
                    "Authorization" => "Bearer ".env("TOKEN_MELHOR_ENVIO"),
                    "User-Agent" => "rubens.jesus1997@gmail.com",
                    "Content-Type" =>  "application/json",
                    "Accept" => "application/json"
                    ]
            ])->post($url, $body);
            $data = $response->json();
            return response()->json(['data' => $data], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    //Saldo do usuário
    public function saldoMelhorEnvio(){
        try {
            $url = env("MELHOR_ENVIO").'balance';
            $response = Http::withoutVerifying()->withOptions([
                'headers' => [
                    "Authorization" => "Bearer ".env("TOKEN_MELHOR_ENVIO"),
                    "User-Agent" => "rubens.jesus1997@gmail.com",
                    "Accept" => "application/json"
                    ]
            ])->get($url);
            $data = $response->json();
            return response()->json(['data' => $data], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    //Adicionar valor no saldo da conta
    public function adicionaSaldo(Request $request){
        try {
            $url = env("MELHOR_ENVIO").'balance';
            
            $body = [
                "gateway" => $request->gateway,
                "redirect" => $request->redirect,
                "value" => $request->value
            ];
            $response = Http::withoutVerifying()->withOptions([
                'headers' => [
                    "Authorization" => "Bearer ".env("TOKEN_MELHOR_ENVIO"),
                    "User-Agent" => "rubens.jesus1997@gmail.com",
                    "Content-Type" =>  "application/json",
                    "Accept" => "application/json"
                    ]
            ])->post($url, $body);
            $data = $response->json();
            return response()->json(['data' => $data], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
