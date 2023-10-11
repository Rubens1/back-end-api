<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Estoque;
use Illuminate\Support\Facades\{Hash, Validator};

class EstoqueController extends Controller
{
    /**
     * Registra no estoque
     */
    public function criarComEstoque(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "id_produto" => "required|unique:estoque",
            'id_origem' => 'nullable|integer',
            'id_destino' => 'nullable|integer',
            'id_pedido' => 'nullable|integer',
            'qtd' => 'nullable|integer',
            'id_pedido_item' => 'nullable|integer',
            'id_compra' => 'nullable|integer',
            'opt' => 'nullable|string|max:18',
            'datahora' => 'nullable|date',
            'obs' => 'nullable|string|max:255',
            'finalidade' => 'nullable|in:USO_CONSUMO,VENDA,INDEFINIDO,CONSERTO,INVESTIMENTO',
            'grupo' => 'nullable|string|max:255',
            'id_agente' => 'nullable|integer',
            'datahora_reg' => 'nullable|date',
            'id_agente_remocao' => 'nullable|integer',
            'removido' => 'nullable|integer',
            'datahora_remocao' => 'nullable|date',
            'nf' => 'nullable|string|max:255',
            'id_nf' => 'nullable|integer',
            'preco_compra' => 'nullable|numeric|between:0,9999999.99',
            'preco_vendido' => 'nullable|numeric|between:0,9999999.99',
            'sn' => 'nullable|string|max:255',
        ]);
    }

    /**
     * Registra no estoque caso se o produto estiver no estoque
     */
    public function registraNoEstoque(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "id_produto" => "required|unique:estoque",
            'id_origem' => 'nullable|integer',
            'id_destino' => 'nullable|integer',
            'id_pedido' => 'nullable|integer',
            'qtd' => 'nullable|integer',
            'id_pedido_item' => 'nullable|integer',
            'id_compra' => 'nullable|integer',
            'opt' => 'nullable|string|max:18',
            'datahora' => 'nullable|date',
            'obs' => 'nullable|string|max:255',
            'finalidade' => 'nullable|in:USO_CONSUMO,VENDA,INDEFINIDO,CONSERTO,INVESTIMENTO',
            'grupo' => 'nullable|string|max:255',
            'id_agente' => 'nullable|integer',
            'datahora_reg' => 'nullable|date',
            'id_agente_remocao' => 'nullable|integer',
            'removido' => 'nullable|integer',
            'datahora_remocao' => 'nullable|date',
            'nf' => 'nullable|string|max:255',
            'id_nf' => 'nullable|integer',
            'preco_compra' => 'nullable|numeric|between:0,9999999.99',
            'preco_vendido' => 'nullable|numeric|between:0,9999999.99',
            'sn' => 'nullable|string|max:255',
        ]);

        if (count($request->input()) == 0) {
            return response()->json([
                "status" => "ERROR",
                "message" => "Para editar o registrar algum item no estoque passe ao menos algum campo valído, não passe campos vazios."
            ], 400);
        }

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $produto = Produtos::where("id", $request->id_produto)->first();

        if ($produto == null || !$produto) {
            return response()->json([
                "status" => "ERROR",
                "message" => "O índice do produto é inexistente."
            ], 400);
        }


        try {
            Estoque::create($request->all());

            return response()->json([
                "status" => "success",
                "message" => "Produto adicionado ao estoue"
            ]);
        } catch (Exception $e) {
            echo $e->getMessage();
            return response()->json([
                "status" => "ERROR",
                "message" => "Desculpe estamos enfrentando problemas internos."
            ]);
        }
    }

    /**
     * Editar estoque
     */
    public function editar(Request $request, $id_produto)
    {
        try {
            $estoque = Estoque::where("id_produto", $id_produto)
                ->first();
            
            $estoque->id_origem = $request->id_origem ?? $estoque->id_origem;
            $estoque->id_destino = $request->id_destino ?? $estoque->id_destino;
            $estoque->id_pedido_item = $request->pedido_item ?? $estoque->id_pedido_item;
            $estoque->id_compra = $request->id_pedido_compra ?? $estoque->id_pedido_compra;
            $estoque->opt = $request->opt ?? $estoque->opt;
            $estoque->finalidade = $request->finalidade ;

        } catch (Exception $e) {
        }
    }

    /**
     * Remover do estoque
     */
    public function removerDoEstoque(Request $request, $id_produto)
    {
        try {
            $validator = validator::make($request->only("qtd"), [
                "qtd" => "required|min:1|integer"
            ]);

            $mock_user = [
                "pessoa_id" => 1,
                "nome" => "Lucas Souza Silva"
            ];

            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            $estoque = Estoque::where("id_produto", $id_produto)
                ->with("produto")
                ->first();

            if (!$estoque || $estoque == null) {
                return response()->json([
                    "status" => "ERROR",
                    "messae" => "Índice fornecido é ínvalido ou inexistente"
                ], 400);
            }

            //Updates current quantity of products from stock
            $estoque->qtd = $estoque->qtd - $request->qtd;

            $plural = $request->qtd == 1 ? null : "s";

            $log_info = "O usuario retirou a quantidade de {$request->qtd} unidade" . $plural . " do item {$estoque->produto->nome} ás " . date("H:i") . " na data " . date("d-m-Y");

            Logger::info([
                "obs" => $log_info,
                "id_pessoa" => 1,
                "id_pedido" => 4,
                "id_agente" => 1,
                "id_produto" => 1,
                "ip" => $request->ip(),

            ]);

            $estoque->save();

            return response()->json([
                "status" => "SUCCESS",
                "message" => "Produto retirado do estoque"
            ]);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * Adicionar no estoque
     */
    public function adicionarNoEstoque(Request $request, $id_produto)
    {
        try {
            $validator = validator::make($request->only("qtd"), [
                "qtd" => "required|min:1|integer"
            ]);

            $mock_user = [
                "pessoa_id" => 1,
                "nome" => "Lucas Souza Silva"
            ];

            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            $estoque = Estoque::where("id_produto", $id_produto)
                ->with("produto")
                ->first();

            if (!$estoque || $estoque == null) {
                return response()->json([
                    "status" => "ERROR",
                    "messae" => "Índice fornecido é ínvalido ou inexistente"
                ], 400);
            }

            $estoque->qtd = $estoque->qtd + $request->qtd;

            $plural = $request->qtd == 1 ? null : "s";

            $log_info = "O usuario adicionou a quantidade de {$request->qtd} unidade" . $plural . " do item {$estoque->produto->nome} ás " . date("H:i") . " na data " . date("d-m-Y");

            Logger::info([
                "obs" => $log_info,
                "id_pessoa" => 1,
                "id_pedido" => 4,
                "id_agente" => 1,
                "id_produto" => 1,
                "ip" => $request->ip(),

            ]);

            $estoque->save();

            return response()->json([
                "status" => "SUCCESS",
                "message" => "Produto adicionado ao estoque."
            ]);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

}
