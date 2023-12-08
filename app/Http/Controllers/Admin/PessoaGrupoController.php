<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Hash,Validator};
use App\Models\PessoasGrupos;
use Exception;

class PessoaGrupoController extends Controller
{
    /**
     * Lista todos os grupos de pessoas
     */
    public function listar()
    {
        return PessoasGrupos::all();
    }

    /**
     * Cadastra Grupo
     */
    public function cadastrar(Request $request)
    {
        $validator = validator::make($request->all(), [
            'id_pessoa' => 'integer',
            'id_grupo' => 'integer',
        ]);
    
        if ($validator->fails()) {
            return response()->json(["errors" => $validator->errors()], 400);
        }
        try {
            PessoasGrupos::create($request->all());
        
        return response()->json([
            "status" => "Success",
            "message" => "Grupo de pessoas adicionada"
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
     * Edita o grupo de pessoas
     */
    public function editar(Request $request, $id)
    {
        try {
            $grupo = PessoasGrupos::where("id", $id)->first();


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
        $res = PessoasGrupos::where('id', $id)->delete();

        if ($res) {
            $data = [
                'status' => 'success',
                'msg' => 'Grupo de pessoas excluida com sucesso'
            ];
        } else {
            $data = [
                'status' => 'error',
                'msg' => 'Falha em excluir o grupo de pessoas'
            ];
        }
        return response()->json($data);
    }
}
