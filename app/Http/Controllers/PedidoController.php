<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{
    Pedidos,
    PedidosItens
};
use Illuminate\Support\Facades\{Hash, Validator};

class PedidoController extends Controller
{
     /**
     * Mostra todos os pedidos
     */
    public function lista()
    {
        $pedidos = Pedidos::where("is_active", 1)
            ->paginate(10);

        return response()->json([
            'data' => $pedidos
        ]);
    }

    /**
     * Mostrar o pedido do cliente
    */
    public function clienteId(Request $request, $cliente_id)
    {
        $pedidos = Pedidos::where("id_cliente", $cliente_id)->paginate(10);

        if ($pedidos->count() == 0) {
            return response()->json([
                "status" => "error",
                "message" => "O índice fornecido está invalído ou inexistente."
            ], 400);
        }

        return response()->json([
            'data' => $pedidos
        ]);
    }

    /**
     * Mostrar o pedido para o vendedor
    */
    public function VendedorId(Request $request, $id)
    {
        $pedidos = Pedidos::where("id_vendedor", $id)->paginate(10);

        if ($pedidos->count() == 0) {
            return response()->json([
                "status" => "error",
                "message" => "O índice fornecido está invalído ou inexistente."
            ], 400);
        }

        return response()->json([
            'data' => $pedidos
        ]);
    }

    /**
     * Orderna o status dos pedidos
    */
    public function orderStatus(Request $request)
    {

        if ($request->query("sit_pagto")) {
            $pedidos = Pedidos::where("sit_pagto", $request->query("sit_pagto"))->paginate();

            if ($request->query() == 0 || !$pedidos) {
                return response()->json([
                    "status" => "error",
                    "message" => "Parametro invalido"
                ]);
            }

            return Presponse()->json([
                'data' => $pedidos
            ]);
        }

        if ($request->query("sit_pedido")) {
            $pedidos = Pedidos::where("sit_pedido", $request->query("sit_pedido"))->paginate();

            if ($request->query() == 0) {
                return response()->json([
                    "status" => "error",
                    "message" => "Parametro invalido"
                ]);
            }

            return response()->json([
                'data' => $pedidos
            ]);
        }

        if ($request->query("sit_entrega")) {
            $pedidos = Pedidos::where("sit_entrega", $request->query("sit_entrega"))->paginate();

            if ($request->query() == 0) {
                return response()->json([
                    "status" => "error",
                    "message" => "Parametro invalido"
                ]);
            }

            return response()->json([
                'data' => $pedidos
            ]);
        }

        if ($request->query("sit_producao")) {
            $pedidos = Pedidos::where("sit_entrega", $request->query("sit_producao"))->paginate();

            if ($request->query() == 0) {
                return response()->json([
                    "status" => "error",
                    "message" => "Parametro invalido ou nçao"
                ]);
            }

            return response()->json([
                'data' => $pedidos
            ]);
        }

        return response()->json([
            "status" => "error",
            "message" => "Paramentros invalidos."
        ]);
    }

    /**
     * Mostrar o pedido do cliente
    */
    public function pedidoId(Request $request, $id)
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
     * Mostrar o pedido detalhado do cliente
    */
    public function listaclienteId(Request $request, $id)
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
     * Mostrar o pedido detalhado para o vendedor
    */
    public function vendedorLista(Request $request, $id)
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
     * Criar pedido
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
