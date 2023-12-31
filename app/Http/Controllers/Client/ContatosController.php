<?php

namespace App\Http\Controllers\Client;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Contatos;
use Exception;
use Illuminate\Support\Facades\{Hash, Validator};

class ContatosController extends Controller
{
     /**
     * Lista de contatos pelo id
     */
     public function listar(Request $request, $id_pessoa)
     {
        try {
            $contatos = Contatos::where("id_pessoa", $id_pessoa)->get();
    
            if($contatos->count() == 0) {
               return response()->json([
                   "status" => "error",
                   "message" => "Usuário não possui nenhum contato cadastrado."
               ], 400);
           }
   
            return response()->json([
               "data" => $contatos,
               "quantidade" => $contatos->count()
           ], 200);
        }catch (Exception $th) {
            return response()->json([
                "status" => "error",
                "message" => "Desculpe estamos enfrentado problemas internos.",
                "error" => $e->getMessage()
            ]);   
        }
    }
     /**
     * Cadastro de contato
     */
    public function cadastrar(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'string|email|max:50',
            'telefone' => 'string|max:255',
            'celular' => 'string|max:255',
            'id_pessoa' => 'required|exists:pessoas,id',
        ]);

        
        if ($validator->fails()) {
            return response()->json([
                "errors" => $validator->errors()
            ], 400);
        }

        try {
            $contato = Contatos::create(
                array_merge(
                    $request->all(),
                    [
                        "principal" => false
                    ]
                )
            )->fresh();

            return response()->json([
                "status" => "success",
                'message' => 'Contato cadastrado com sucesso',
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
     * Editar de contato
     */
    public function editar(Request $request, $id){

        try {
            $contato = Contatos::where("id", $id)->first();

            $contato->email = $request->email ?? $contato->email;
            $contato->telefone = $request->telefone ?? $contato->telefone;
            $contato->celular = $request->celular ?? $contato->celular;
           
            $contato->save();

            return response()->json([
                "message" => "Dados atualizados com sucesso",
                "data" => $contato
            ],200);

        } catch (Exception $e) {
            return response()->json([
                "error" => $e->getMessage()
            ], 500);
        }

    }

    /**
     * Escolher o contato principal
     */
    public function principal(Request $request, $id){

        try {
            $contato = Contatos::where("id", $id)->first();


            $contato->principal = $request->principal ?? $contato->principal;

            $contato->save();

            return response()->json([
                "message" => "Dados atualizados com sucesso",
                "fresh" => $contato
            ],200);

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

        $res = Contatos::where('id', $id)->delete();

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
