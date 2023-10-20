<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mail\OrdersStatusMail;
use App\Jobs\ProcessMailQueue;
use Illuminate\Support\Facades\{
    Hash,
    Mail,
    Validator
};
use App\Models\{
    Pedidos,
    PedidosItens
};

use Exception;

class GerenciamentoPedidoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:pessoas');
    }

    /**
     * Atualiza status de pagamento e envia um email para o cliente
     * informando o status do pagamento.
     */
    public function atualizarStatusPagamento(Request $request, $id_pedido)
    {

        $validator = Validator::make($request->only("sit_pagto"), [
            "sit_pagto" => "required|max:30"
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $pedido = Pedidos::where("id", $id_pedido)
            ->with("cliente")
            ->first();

        try {
            $pedido->sit_pagto = $request->sit_pagto;

            $nome = explode(" ", $pedido->cliente->nome);

            $subject = "Atualizaçao do seu pedido";

            $message = "Olá " . $nome[0] . ", o status de pagamento do seu pedido mudou para " . $request->sit_pagto . ".";


            ProcessMailQueue::dispatch(
                $pedido->cliente->email,
                $nome[0],
                $subject,
                $message
            );

            $pedido->save();

            return response()->json([
                "status" => "success",
                "message" => "Status do pedido atualizado para " . $request->sit_pedido
            ]);
        } catch (Exception $e) {
            return response()->json([
                "status" => "error",
                "message" => "parametros invalidos"
            ]);
        }
    }

    /**
     * Atualiza o status do pedido e envia um email para o cliente
     * informando o status do pedido.
     */
    public function atualizarStatusPedido(Request $request, $id_pedido)
    {

        try {
            $validator = Validator::make($request->only("sit_pedido"), [
                "sit_pedido" => "required|max:30"
            ]);

            $pedido = Pedidos::where("id", $id_pedido)
                ->with(["cliente"])
                ->first();


            if ($validator->fails()) {
                return response()->json($validator->errors());
            }


            $pedido->sit_pedido = $request->sit_pedido;

            $subject = "Atualizaçao do status do seu pedido";

            $nome = explode(" ", $pedido->cliente->nome);

            $message = "Olá " . $nome[0] .  ", o status seu pedido mudou para " . strtolower($request->sit_pedido) . ".";


            ProcessMailQueue::dispatchAfterResponse(
                $pedido->cliente->email,
                $nome[0],
                $message,
                $subject
            );

            return response()->json([
                "status" => "success",
                "message" => "Status de pedido atualizado para: " . $request->sit_pedido
            ]);
        } catch (Exception $e) {
            echo $e->getMessage();

            return response()->json([
                "status" => "error",
                "message" => "parametros invalidos"
            ]);
        }
    }

    /**
     * Atualiza os status da entrega caso for entrega própria
     * e envia um email para usuário
     */
    public function atualizarStatusEntrega(Request $request, $id_pedido)
    {
        $validator = Validator::make($request->only("sit_entrega"), [
            "sit_entrega" => "required|max:30"
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $pedido = Pedidos::where("id", $id_pedido)
            ->with("cliente")
            ->first();

        try {
            $pedido->sit_entrega = $request->sit_entrega;

            $nome = explode(" ", $pedido->cliente->nome);

            $subject = "Atualizaçao do status do seu pedido";

            $message = "Olá " . $nome[0] . ", o status da entrega do seu pedido mudou para " . $request->sit_pagto . ".";

            ProcessMailQueue::dispatch(
                $pedido->cliente->email,
                $nome[0],
                $subject,
                $message
            );

            $pedido->save();

            return response()->json([
                "status" => "success",
                "message" => "Status de pagamento atuaalizado"
            ]);
        } catch (Exception $e) {
            return response()->json([
                "status" => "error",
                "message" => "parametros invalidos"
            ]);
        }
    }

    /**
     * Atualiza o status de produção e envia um email para o cliente 
     * informando o status da produção.
     */
    public function atualizarStatusProducao(Request $request, $id_pedido)
    {
        $validator = Validator::make($request->only("sit_producao"), [
            "sit_producao" => "required|max:30"
        ]);

        $pedido = Pedidos::where("id", $id_pedido)
            ->with("cliente")
            ->first();

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        try {
            $pedido->sit_pedido = $request->sit_producao;

            $subject = "Atualizaçao do status de produção do seu pedido.";

            $nome = explode(" ", $pedido->cliente->nome);

            $message = "Olá " . $nome[0] . "o status da entrega do seu pedido mudou para " . $request->sit_producao . ".";

            ProcessMailQueue::dispatch(
                $pedido->cliente->email,
                $nome[0],
                $subject,
                $message
            );

            $pedido->save();

            return response()->json([
                "status" => "success",
                "message" => "Status de pagamento atuaalizado para: " . $request->sit_producao . "."
            ]);
        } catch (Exception $e) {
            return response()->json([
                "status" => "error",
                "message" => "parametros invalidos"
            ]);
        }
    }
}
