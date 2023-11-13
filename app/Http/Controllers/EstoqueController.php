<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Helpers\Logger;
use Illuminate\Http\Request;
use App\Models\{
    Produtos,
    Estoque,    
    Actions,
    ActionsHistory
};
use Exception;
use GuzzleHttp\Promise\Create;
use Illuminate\Support\Facades\{
    Hash,
    Validator
};

class EstoqueController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:pessoas');
    }


    public function ObterEstoque(Request $request)
    {
        try {
            $estoque = Estoque::with([
                "produto"
            ])->paginate(10);

            return response()->json($estoque);
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
    /**
     * registra um produto no estoque passando o id do produto
     */
    public function registrarProdutoEmEstoque(Request $request)
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
            return response()->json(["d" => $request->input(), $validator->errors()], 400);
        }

        $produto = Produtos::where("id", $request->id_produto)->first();

        if ($produto == null || !$produto) {
            return response()->json([

                "status" => "ERROR",
                "message" => "O índice do produto é inexistente."
            ], 400);
        }


        try {
            $pc_values = [
                "pc_vendido" => number_format($request->p_vendido / 100, 2, ".", ","),
                "pc_compra" => number_format($request->p_compra / 100, 2, ".", ","),
            ];

            $data = array_merge(
                $request->all(),
                [
                    "preco_compra" => str_replace(",", "", $pc_values["pc_vendido"]),
                    "preco_vendido" => str_replace(",", "", $pc_values["pc_compra"])    
                ]
            );

            Estoque::create($data);

            return response()->json([
                "status" => "success",
                "message" => "Produto adicionado ao estoque"
            ]);
        } catch (Exception $e) {

            return response()->json([
                "s" => $e->getMessage(),
                "status" => "ERROR",
                "message" => "Desculpe estamos enfrentando problemas internos."
            ], 500);
        }
    }

    /**
     * Edita informações do estoque exceto o preço
     */
    public function editarEstoque(Request $request, $id_produto)
    {
        $keys = [];

        try {

            $validator = Validator::make($request->all(), [
                'id_origem' => ['nullable', 'integer'],
                'id_destino' => ['nullable', 'integer'],
                'id_pedido' => ['nullable', 'integer'],
                'qtd' => ['nullable', 'integer'],
                'id_pedido_item' => ['nullable', 'integer'],
                'id_compra' => ['nullable', 'integer'],
                'opt' => ['nullable', 'string', 'max:18'],
                'datahora' => ['nullable', 'date'],
                'obs' => ['nullable', 'string', 'max:255'],
                'finalidade' => ['nullable', 'string', 'in:USO_CONSUMO,VENDA,INDEFINIDO,CONSERTO,INVESTIMENTO'],
                'grupo' => ['nullable', 'string', 'max:255'],
                'id_agente' => ['nullable', 'integer'],
                'datahora_reg' => ['nullable', 'date'],
                'id_agente_remocao' => ['nullable', 'integer'],
                'removido' => ['nullable', 'integer'],
                'datahora_remocao' => ['nullable', 'date'],
                'nf' => ['nullable', 'string', 'max:255'],
                'id_nf' => ['nullable', 'integer'],
                'preco_compra' => ['nullable', 'numeric', 'max:9999999.99'],
                'preco_vendido' => ['nullable', 'numeric', 'max:9999999.99'],
                'sn' => ['nullable', 'string', 'max:255'],
            ]);

            if (count($request->input()) == 0) {
                return response()->json([
                    "status" => "ERROR",
                    "message" => "Para editar o produto passe ao menos algum campo valído não passe campos vazios."
                ], 400);
            }

            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            $estoque = Estoque::where("id_produto", $id_produto)
                ->first();

            if (!$estoque || $estoque == null) {
                return response()->json([
                    'status' => "ERROR",
                    "message"
                ]);
            }


            foreach ($request->input() as $key => $value) {
                array_push($keys, $key);
            }

            foreach ($keys as $key) {
                $estoque->update([
                    $key => $request->$key ?? $estoque->$key
                ]);
            }

            $estoque->save();

            return response()->json([
                "message" => "Dados do estoque atualizado",
                "fresh" => $estoque
            ]);


        } catch (Exception $e) {

        }
    }

    /**
     * retira algum item do estoque e depois insere na tabela actions_history
     * passando a observações
     */
    public function retirarProdutoEstoque(Request $request, $id_produto)
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
     * adiciona algum item no estoque e depois insere na tabela actions_history
     * passando a observações
     */
    public function adicionarAoEstoque(Request $request, $id_produto)
    {
        try {
            $validator = validator::make($request->only("qtd"), [
                "qtd" => "required|min:1|integer"
            ]);


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