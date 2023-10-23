<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Hash,Validator};
use App\Models\Grupos;
use Exception;

class GrupoController extends Controller
{
    /**
     * Lista todos os grupos
     */
    public function listar()
    {
        return Grupos::all();
    }

    /**
     * Cadastra Grupo
     */
    public function cadastrar(Request $request)
    {
        $validator = validator::make($request->all(), [
            'grupo' => 'required|string',
            'permissoes' => 'string',
        ]);
    
        if ($validator->fails()) {
            return response()->json(["errors" => $validator->errors()], 400);
        }
        try {
            Grupos::create($request->all());
        
        return response()->json([
            "status" => "SUCCESS",
            "message" => "Grupo adicionada"
        ]);
        }catch (Exception $e) {
            return response()->json([
                "status" => "ERROR",
                "message" => "Desculpe estamos com problemas",
                "error" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Edita a grupo
     */
    public function editar(Request $request, $id)
    {
        try {
            $grupo = Grupos::where("id", $id)->first();


            $grupo->grupo = $request->grupo ?? $grupo->grupo;
            $grupo->id_grupo = $request->permissoes ?? $grupo->permissoes;

            $categoria->save();

            return response()->json([
                "message" => "Dados atualizados com sucesso",
                "fresh" => $categoria
            ]);

        } catch(Exception $e) {
            return response()->json([
                "error" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Deletar registro pelo id
     */
    public function excluir($id)
    {
        $res = Grupos::where('id', $id)->delete();

        if ($res) {
            $data = [
                'status' => '1',
                'msg' => 'Grupo excluida com sucesso'
            ];
        } else {
            $data = [
                'status' => '0',
                'msg' => 'Falha em excluir o grupo'
            ];
        }
        return response()->json($data);
    }
}
