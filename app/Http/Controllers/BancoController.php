<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bancos;
use Exception;
use Illuminate\Support\Facades\{Hash, Validator};

class BancoController extends Controller
{
     /**
     * Lista de contatos pelo id
     */
    public function lista(Request $request, $id_pessoa)
    {
        $banco = Bancos::where("id_pessoa", $id_pessoa)->first();

        return response()->json($banco);
    }

    /**
    * Cadastro de contato
    */
   public function cadastrar(Request $request){
        $validator = Validator::make($request->all(), [
            'pix' => 'string|max:255',
            'agencia' => 'string|max:255',
            'conta' => 'string|max:255',
            'id_pessoa' => 'required|exists:pessoas,id',
        ]);

        
        if ($validator->fails()) {
            return response()->json([
                "errors" => $validator->errors()
            ], 400);
        }

        try {
            $contato = Bancos::create(
                array_merge($request->all())
            )->fresh();

            return response()->json([
                "status" => "success",
                'message' => 'Dados bancario cadastrado com sucesso',
                'contato' => $contato
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                "status" => "error",
                "message" => "Desculpe estamos enfrentado problemas internos.",
                "error" => $e->getMessage()
            ]);  
    }
   }
    /**
    * Editar de Banco
    */
   public function editar(Request $request, $id){
    try {
        $banco = Bancos::where("id", $id)->first();


        $banco->pix = $request->pix ?? $banco->pix;
        $banco->agencia = $request->agencia ?? $banco->agencia;
        $banco->conta = $request->conta ?? $banco->conta;
       
        $banco->save();

        return response()->json([
            "message" => "Dados atualizados com sucesso",
            "fresh" => $contato
        ]);

    } catch (Exception $e) {
        return response()->json([
            "error" => $e->getMessage()
        ], 500);
    }
   }

    /**
    * Excluir de contato
    */
   public function excluir(Request $request, $id){

    $res = Bancos::where('id', $id)->delete();

        if ($res) {
            $data = [
                'status' => '1',
                'msg' => 'Contato excluida com sucesso'
            ];
        } else {
            $data = [
                'status' => '0',
                'msg' => 'Falha em excluir a contato'
            ];
        }
        return response()->json($data);
   }
}
