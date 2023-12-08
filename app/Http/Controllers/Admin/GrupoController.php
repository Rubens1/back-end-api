<?php

namespace App\Http\Controllers\Admin;

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
            "status" => "Success",
            "message" => "Grupo adicionada"
        ]);
        }catch (Exception $e) {
            return response()->json([
                "status" => "Error",
                "message" => "Desculpe estamos com problemas",
                "error" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Edita o grupo
     */
    public function editar(Request $request, $id)
    {
        try {
            $grupo = Grupos::where("id", $id)->first();


            $grupo->grupo = $request->grupo ?? $grupo->grupo;
            $grupo->id_grupo = $request->permissoes ?? $grupo->permissoes;

            $grupo->save();

            return response()->json([
                "message" => "Dados atualizados com sucesso",
                "fresh" => $grupo
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
                'status' => 'success',
                'msg' => 'Grupo excluida com sucesso'
            ];
        } else {
            $data = [
                'status' => 'error',
                'msg' => 'Falha em excluir o grupo'
            ];
        }
        return response()->json($data);
    }
}
