<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Hash, Validator};
use App\Models\Categorias;
use Exception;

class CategoriaController extends Controller
{
    /**
     * Lsita todas as categoria.
     */
    public function listar(Request $request)
    {
        $categoria = Categorias::leftJoin('categorias AS categoriaPai', 'categorias.id_categoria', '=', 'categoriaPai.id')
            ->select('categorias.*', 'categoriaPai.categoria AS nome')
            ->paginate(10);
        return response()->json($categoria);
    }

    /**
     * Deletar registro pelo id
     */
    public function excluir($id)
    {
        $res = Categorias::where('id', $id)->delete();

        if ($res) {
            $data = [
                'status' => '1',
                'msg' => 'Categoria excluida com sucesso'
            ];
        } else {
            $data = [
                'status' => '0',
                'msg' => 'Falha em excluir a categoria'
            ];
        }
        return response()->json($data);
    }
    
    /**
     * Mostra info de uma categoria pegando o id
     */
    public function info($id)
    {
        $categoria = Categorias::leftJoin('categorias AS categoriaPai', 'categorias.id_categoria', '=', 'categoriaPai.id')
            ->where('categorias.id', '=', $id)
            ->select('categorias.*', 'categoriaPai.categoria AS nome', 'categoriaPai.id AS idPai')
            ->get();

        return $categoria;
    }

    /**
     * Edita a categoria
     */
    public function editar(Request $request, $id)
    {
        try {
            $categoria = Categorias::where("id", $id)->first();


            $categoria->categoria = $request->categoria ?? $categoria->categoria;
            $categoria->id_categoria = $request->id_categoria ?? $categoria->id_categoria;
            $categoria->descricao = $request->descricao ?? $categoria->descricao;
            $categoria->keywords = $request->keywords ?? $categoria->keywords;
            $categoria->ativo = $request->ativo ?? $categoria->ativo;

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
     * Cria uma categoria.
     */
    public function criarCategoria(Request $request)
    {
        $validator = validator::make($request->all(), [
            "categoria" => "required|string|max:100",
            "show_cardpress" => "nullable",
            "id_categoria" => "nullable|integer",
            "descricao" => "required",
            "keywords" => "required",
            "capa" => "nullabe",
            "ativo" => "nullable"
        ]);

        if ($validator->fails()) {
            return response()->json(["errors" => $validator->errors()], 400);
        }

        try {
            Categorias::create($request->all());

            return response()->json([
                "status" => "SUCCESS",
                "message" => "Categoria adicionada"
            ]);
        } catch (Exception $e) {
            return response()->json([
                "status" => "ERROR",
                "message" => "Desculpe estamos com problemas",
                "error" => $e->getMessage()
            ], 500);
        }
    }
}
