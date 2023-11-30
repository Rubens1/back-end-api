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
        
            $characters = '0123456789';
            $length = mt_rand(8, 8);

            $randomNumber = '';

            for ($i = 0; $i < $length; $i++) {
                $randomNumber .= $characters[mt_rand(0, strlen($characters) - 1)];
            }

            $data = array_merge([
                $request->input(),
                "numero_pedido" => $randomNumber,
                "id_cliente" => $request->id_cliente,
                "id_op_frete" => $request->id_op_frete,
                "id_op_pagto" => $request->id_op_pagto,
                "id_endereco" => $request->id_endereco,
                "id_pessoa" => $request->id_pessoa,
                
            ]);

            
            $pedidos = Pedidos::create($data)->fresh();
            
            foreach($request->itens as $key => $value) {

                PedidosItens::create([
                    "id_produto" => $value["id"],
                    "total" => $value['total'],
                    "preco" => $value['preco'],
                    'qtd' => $value['quantidade'],
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
}
