<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categorias;
use Illuminate\Support\Facades\{Hash, Validator};

class CategoriaController extends Controller
{
    
     /**
     * Lista de categoria
     */
    public function lista(Request $request)
    {
        $categoria = Categorias::leftJoin('categorias AS categoriaPai', 'categorias.id_categoria', '=', 'categoriaPai.id')
        ->select('categorias.*', 'categoriaPai.categoria AS nome')
        ->get();
        return response()->json($categoria);
    }

    /**
     * Cadastro da categoria
     */
    public function cadastrar(Request $request) 
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
            return response()->json($validator->errors(), 400);
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
                "message" => "Desculpe estamos com problema"
            ]);
        }
    }

    /**
     * Mostra info de uma categoria pegando o id
     */
    public function info($id) {
        $categoria = Categorias::leftJoin('categorias AS categoriaPai', 'categorias.id_categoria', '=', 'categoriaPai.id')
        ->where('categorias.id','=',$id)
        ->select('categorias.*', 'categoriaPai.categoria AS nome', 'categoriaPai.id AS idPai')
        ->get();

        return $categoria;
    }
}
