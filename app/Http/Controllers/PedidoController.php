<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\PedidosResource;
use App\Jobs\ProcessMailQueue;
use App\Models\{
    Pedidos,
    PedidosItens,
    Produtos,
    CategoriaProdutos
};
use Exception;
use Illuminate\Support\Facades\{
    Hash,
    Validator
};

use function Laravel\Prompts\error;

class PedidoController extends Controller
{
    /**
     * Lista de forma resumida e paginada todos os pedidos
     */
    public function listar()
    {
        $pedidos = Pedidos::where("is_active", 1)
            ->paginate(10);

        return PedidosResource::collection($pedidos);
    }

    /**
     * Lista todos pediddos de forma resumida e paginada 
     * todos pedidos de um cliente passando o id do cliente
     */

    public function listaPorClienteId(Request $request, $cliente_id)
    {
        $pedidos = Pedidos::where("id_cliente", $cliente_id)->paginate(10);

        if ($pedidos->count() == 0) {
            return response()->json([
                "status" => "error",
                "message" => "O índice fornecido está invalído ou inexistente."
            ], 400);
        }

        return PedidosResource::collection($pedidos);
    }

    /**
     * Lista de forma resumida de todas as vendas do vendedor 
     * passando o seu id
     */
    public function listarPorVendedorId(Request $request, $id)
    {
        $pedidos = Pedidos::where("id_vendedor", $id)->paginate(10);

        if ($pedidos->count() == 0) {
            return response()->json([
                "status" => "error",
                "message" => "O índice fornecido está invalído ou inexistente."
            ], 400);
        }

        return PedidosResource::collection($pedidos);
    }

    /**
     * Filtra todos pedidos pelo status passando uma query string na URL
     * ?sit_pagto, ?sit_pedido, ?sit_entrega, ?sit_producao
     * Caso não passe nenhum parâmetro na URL será retornado uma mensagem
     * de erro informando que não foi passado nenhum parâmetro
     */
    public function listarPorStatus(Request $request)
    {

        if ($request->query("sit_pagto")) {
            $pedidos = Pedidos::where("sit_pagto", $request->query("sit_pagto"))->paginate();

            if ($request->query() == 0 || !$pedidos) {
                return response()->json([
                    "status" => "error",
                    "message" => "Parametro invalido"
                ]);
            }

            return PedidosResource::collection($pedidos);
        }

        if ($request->query("sit_pedido")) {
            $pedidos = Pedidos::where("sit_pedido", $request->query("sit_pedido"))->paginate();

            if ($request->query() == 0) {
                return response()->json([
                    "status" => "error",
                    "message" => "Parametro invalido"
                ]);
            }

            return PedidosResource::collection($pedidos);
        }

        if ($request->query("sit_entrega")) {
            $pedidos = Pedidos::where("sit_entrega", $request->query("sit_entrega"))->paginate();

            if ($request->query() == 0) {
                return response()->json([
                    "status" => "error",
                    "message" => "Parametro invalido"
                ]);
            }

            return PedidosResource::collection($pedidos);
        }

        if ($request->query("sit_producao")) {
            $pedidos = Pedidos::where("sit_entrega", $request->query("sit_producao"))->paginate();

            if ($request->query() == 0) {
                return response()->json([
                    "status" => "error",
                    "message" => "Parametro invalido ou nçao"
                ]);
            }

            return PedidosResource::collection($pedidos);
        }

        return response()->json([
            "status" => "error",
            "message" => "Paramentros invalidos."
        ]);
    }

    /**
     * Obtem de forma detalhada a informações de um pedido 
     * passando o id do cliente
     */
    public function obtePorPedidoId(Request $request, $id)
    {
        $pedido = Pedidos::where("id", $id)
            ->with([
                "cliente",
                "vendedor",
                "opcaoFrete",
                "opcaoPagamento",
                "endereco",
                "vendedor"
            ])->first();

        if (!$pedido || $pedido == null) {
            return response()->json([
                "status" => "success",
                "message" => "O índice fornecido está invalído ou inexistente."
            ]);
        }

        $itens = PedidosItens::where("id_pedido", $pedido->id)->with(["produto"])
            ->get();

        return response()->json([
            "data" => [
                "detalhes" => $pedido,
                "itens" => $itens

            ]
        ]);
    }

    /**
     * Obtem as informações de forma detalhada de um pedido
     * passando o id do cliente 
     */
    public function obterPorClienteId(Request $request, $id)
    {
        $pedido = Pedidos::where("id_cliente", $id)
            ->with([
                "cliente",
                "vendedor",
                "opcaoFrete",
                "opcaoPagamento",
                "endereco",
            ])->first();

        if (!$pedido || $pedido == null) {
            return response()->json([
                "status" => "success",
                "message" => "O índice fornecido está invalído ou inexistente."
            ]);
        }

        $itens = PedidosItens::where("id_pedido", $pedido->id)
            ->with(["produto"])
            ->get();

        return response()->json([
            "data" => [
                "detalhes" => $pedido,
                "itens" => $itens

            ]
        ]);
    }

    /**
     * Obtem de forma detalhada as informações de um pedido passando 
     * o id do vendedor
     */
    public function obterPorVendedorId(Request $request, $id)
    {
        $pedido = Pedidos::where("id_vendedor", $id)
            ->with([
                "cliente",
                "vendedor",
                "opcaoFrete",
                "opcaoPagamento",
                "endereco",
            ])->first();

        if (!$pedido || $pedido == null) {
            return response()->json([
                "status" => "success",
                "message" => "O índice fornecido está invalído ou inexistente."
            ]);
        }

        $itens = PedidosItens::where("id_pedido", $pedido->id)
            ->with(["produto"])
            ->get();

        return response()->json([
            "data" => [
                "detalhes" => $pedido,
                "itens" => $itens

            ]
        ]);
    }
   
    /**
     * Cria um pedido pelo vendedor localmente.
     */
    public function criarPedido(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "custo_total" => "required",
            "custo_frete" => "required",
            "vencimento" => "required|date",
            "data_pagto" => "required|date",
            "validade_proposta" => "required|date",
            "comissoes_pagas" => "required",
            "id_cliente" => "integer|required",
            "id_op_frete" => "integer|required",
            "id_op_pagto" => "integer|required",
            "id_vendedor" => "integer",
            "id_endereco" => "required",
            "id_pessoa" => "required"
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        try {
            Pedidos::create($validator->validated());

            return response()->json([
                "status" => "cadastrado com sucesso."
            ]);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}
