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

            foreach ($request->products as $key => $value) {
                $produtos[] = [
                    "name" => $value["name"],
                    "quantity" => $value["quantity"],
                    "unitary_value" => $value["unitary_value"],
                ];
            }

            foreach ($request->volumes as $key => $value) {
                $volumes[] = [
                    "height" => $value["height"],
                    "width" => $value["width"],
                    "length" => $value["length"],
                    "weight" => $value["weight"]
                ];
            }

            $body = [
                "service" => $request->servico,
                "agency" => $request->agencia,
                "from" => [
                    "name" => $request->from["nome"],
                    "phone" => $request->from["telefone"],
                    "email" => $request->from["email"],
                    "document" => $request->from["cpf"],
                    "company_document" => $request->from["cnpj"],
                    "state_register" => $request->from["registro"],
                    "address" => $request->from["endereco"],
                    "complement" => $request->from["complemento"],
                    "number" => $request->from["numero"],
                    "district" => $request->from["bairro"],
                    "city" => $request->from["cidade"],
                    "country_id" => $request->from["id_pais"],
                    "postal_code" => $request->from["cep"],
                    "note" => $request->from["observacao"]
                ],
                "to" => [
                    "name" => $request->to["nome"],
                    "phone" => $request->to["telefone"],
                    "email" => $request->to["email"],
                    "document" => $request->to["cpf"],
                    "company_document" => $request->to["cnpj"],
                    "state_register" => $request->to["registro"],
                    "address" => $request->to["endereco"],
                    "complement" => $request->to["complemento"],
                    "number" => $request->to["numero"],
                    "district" => $request->to["bairro"],
                    "city" => $request->to["cidade"],
                    "state_abbr" => $request->to["id_estado"],
                    "country_id" => $request->to["id_pais"],
                    "postal_code" => $request->to["cep"],
                    "note" => $request->to["observacao"]
                ],
                "products" => $produtos,
                "volumes" => $volumes,
                "options" => [
                    "insurance_value" => $request->options["seguro"],
                    "receipt" => false,
                    "own_hand" => false,
                    "reverse" => false,
                    "non_commercial" => false,
                    "invoice" => [
                        "key" => $request->options["invoice"]["key"]
                    ],
                    "platform" => $request->options["plataforma"]
                ]
            ];

            $response = Http::withoutVerifying()->withOptions([
                'headers' => [
                    "Authorization" => "Bearer ".env("TOKEN_MELHOR_ENVIO"),
                    "User-Agent" => env("EMAIL_MELHOR_ENVIO"),
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
    public function pagarFrete(Request $request){

        try{
            $url = env("MELHOR_ENVIO").'shipment/checkout';

            $body = [
                "orders" => $request->orders,
            ];
            $response = Http::withoutVerifying()->withOptions([
                'headers' => [
                    "Authorization" => "Bearer ".env("TOKEN_MELHOR_ENVIO"),
                    "User-Agent" => env("EMAIL_MELHOR_ENVIO"),
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
    public function gerarEtiqueta(Request $request){
        try{
            $url = env("MELHOR_ENVIO").'shipment/generate';
          
            $body = [
                "orders" => $request->orders,
            ];
            $response = Http::withoutVerifying()->withOptions([
                'headers' => [
                    "Authorization" => "Bearer ".env("TOKEN_MELHOR_ENVIO"),
                    "User-Agent" => env("EMAIL_MELHOR_ENVIO"),
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
            $url = env("MELHOR_ENVIO").'shipment/print';
          
            $body = [
                "mode" =>  $request->mode,
                "orders" => $request->orders
            ];
            $response = Http::withoutVerifying()->withOptions([
                'headers' => [
                    "Authorization" => "Bearer ".env("TOKEN_MELHOR_ENVIO"),
                    "User-Agent" => env("EMAIL_MELHOR_ENVIO"),
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
                    "User-Agent" => env("EMAIL_MELHOR_ENVIO"),
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
                    "User-Agent" => env("EMAIL_MELHOR_ENVIO"),
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
                    "User-Agent" => env("EMAIL_MELHOR_ENVIO"),
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
                    "User-Agent" => env("EMAIL_MELHOR_ENVIO"),
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
            
            $body = [
                "orders" => $request->orders,
            ];
            $response = Http::withoutVerifying()->withOptions([
                'headers' => [
                    "Authorization" => "Bearer ".env("TOKEN_MELHOR_ENVIO"),
                    "User-Agent" => env("EMAIL_MELHOR_ENVIO"),
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

            $body = [
                "orders" => $request->orders,
            ];
            $response = Http::withoutVerifying()->withOptions([
                'headers' => [
                    "Authorization" => "Bearer ".env("TOKEN_MELHOR_ENVIO"),
                    "User-Agent" => env("EMAIL_MELHOR_ENVIO"),
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
                    "User-Agent" => env("EMAIL_MELHOR_ENVIO"),
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
                    "User-Agent" => env("EMAIL_MELHOR_ENVIO"),
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
                    "User-Agent" => env("EMAIL_MELHOR_ENVIO"),
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

    //Cadastra Loja
    public function cadastraLoja(Request $request){
        try {
            $url = env("MELHOR_ENVIO").'companies';
            
            $body = [
                "name" => $request->nome,
                "email" => $request->email,
                "description" => $request->descricao,
                "company_name" => $request->empresa,
                "document" => $request->cnpj,
                "state_register" => $request->ie
            ];
            $response = Http::withoutVerifying()->withOptions([
                'headers' => [
                    "Authorization" => "Bearer ".env("TOKEN_MELHOR_ENVIO"),
                    "User-Agent" => env("EMAIL_MELHOR_ENVIO"),
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
    
    //Lista lojas
    public function listaLojas(){
        try {
            $url = env("MELHOR_ENVIO").'companies';
            $response = Http::withoutVerifying()->withOptions([
                'headers' => [
                    "Authorization" => "Bearer ".env("TOKEN_MELHOR_ENVIO"),
                    "User-Agent" => env("EMAIL_MELHOR_ENVIO"),
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

    //Visualizar loja
    public function verLoja($id){
        try {
            $url = env("MELHOR_ENVIO").'companies/'.$id;
            $response = Http::withoutVerifying()->withOptions([
                'headers' => [
                    "Authorization" => "Bearer ".env("TOKEN_MELHOR_ENVIO"),
                    "User-Agent" => env("EMAIL_MELHOR_ENVIO"),
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

    //Cadastra endereço de loja
    public function cadastraEnderecoLoja(Request $request, $id){
        try {
            $url = env("MELHOR_ENVIO").'companies/'.$id.'/addresses';
            
            $body = [
                "postal_code" => $request->cep,
                "address" => $request->endereco,
                "number" => $request->numero,
                "company_name" => $request->empresa,
                "complement" => $request->complemento,
                "city" => $request->cidade,
                "state" => $request->estado
            ];
            $response = Http::withoutVerifying()->withOptions([
                'headers' => [
                    "Authorization" => "Bearer ".env("TOKEN_MELHOR_ENVIO"),
                    "User-Agent" => env("EMAIL_MELHOR_ENVIO"),
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

    //Lista de endereços de lojas
    public function listaEnderecosLojas($id){
        try {
            $url = env("MELHOR_ENVIO").'companies/'.$id."/addresses";
            $response = Http::withoutVerifying()->withOptions([
                'headers' => [
                    "Authorization" => "Bearer ".env("TOKEN_MELHOR_ENVIO"),
                    "User-Agent" => env("EMAIL_MELHOR_ENVIO"),
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

    //Cadastra telefone de loja
    public function cadastraTelefone(Request $request, $id){
        try {
            $url = env("MELHOR_ENVIO").'companies/'.$id.'/phones';
            
            $body = [
                "type" => $request->tipo,
                "phone" => $request->numero
            ];
            $response = Http::withoutVerifying()->withOptions([
                'headers' => [
                    "Authorization" => "Bearer ".env("TOKEN_MELHOR_ENVIO"),
                    "User-Agent" => env("EMAIL_MELHOR_ENVIO"),
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

    //Lista de telefones de lojas
    public function listaTelefonesLojas($id){
        try {
            $url = env("MELHOR_ENVIO").'companies/'.$id."/phones";
            $response = Http::withoutVerifying()->withOptions([
                'headers' => [
                    "Authorization" => "Bearer ".env("TOKEN_MELHOR_ENVIO"),
                    "User-Agent" => env("EMAIL_MELHOR_ENVIO"),
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
}
