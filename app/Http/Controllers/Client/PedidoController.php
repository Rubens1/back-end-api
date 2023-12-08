<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{
    Pedidos,
    PedidosItens,
};

class PedidoController extends Controller
{
    public function criarPedido(Request $request)
    {
        try {

            //return response()->json($request->input());
            $characters = '0123456789';
            $length = mt_rand(8, 8);

            $randomNumber = '';

            for ($i = 0; $i < $length; $i++) {
                $randomNumber .= $characters[mt_rand(0, strlen($characters) - 1)];
            }



            $pedidos = Pedidos::create([
                "custo_total" => $request->custo_total,
                "numero_pedido" => $randomNumber,
                "id_cliente" => $request->id_cliente,
               // "id_op_frete" => $request->id_op_frete,
                //"id_op_pagto" => $request->id_op_pagto,
                "id_endereco" => $request->id_endereco,
                "id_pessoa" => $request->id_pessoa,
                "forma_pgmt" => $request->forma_pgmt,
                "codigo_pagamento" => $request->codigo_pagamento,
                "codigo_frete" => $request->codigo_frete
            ])->fresh();

            foreach ($request->itens as $key => $value) {

                PedidosItens::create([
                    "id_produto" => $value["id"],
                    "total" => $value['total_preco_itens'],
                    "preco" => $value["estoque"]["preco_vendido"],
                    'qtd' => $value['quantidade'],
                    "id_produto_estoque" => $value["estoque_id"],
                    "numero_pedido" => $pedidos->numero_pedido,
                    "id_pedido" => $pedidos->id
                ]);
            }

            return response()->json(PedidosItens::where("numero_pedido", $pedidos->numero_pedido)->get());

        } catch (\Exception $e) {
            return response()->json([
                "message" => $e->getMessage()
            ], 400);

        }
    }

    public function listarPedidosClientePorId(Request $request, $client_id)
    {
        try {
            $pedidos = Pedidos::where("id_cliente", $client_id)->paginate(5);


            return response()->json($pedidos);

        } catch (\Exception $e) {
            return response()->json([
                "message" => $e->getMessage()
            ], 400);
        }
    }
}
