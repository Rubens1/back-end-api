<?php

namespace App\Http\Controllers\Site;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\{
    Hash,
    Validator
};
use App\Models\{
    Tags
};
use Exception;

class TagController extends Controller
{
    //Lista de tags
    public function lista(Request $request){
        return Tags::all();
    }

    //Cadastra tag
    public function cadastraTag(Request $request){
        $validator = validator::make($request->all(), [
            "nome" => "required|string",
            "url" => "nullabe|string",
        ]);

        if ($validator->fails()) {
            return response()->json(["errors" => $validator->errors()], 400);
        }

        try {
            Tags::create($request->all());

            return response()->json([
                "status" => "success",
                "message" => "Tag cadastrado com sucesso."
            ]);
        } catch (Exception $e) {
            return response()->json([
                "status" => "error",
                "message" => "Desculpe estamos com problemas",
                "error" => $e->getMessage()
            ], 500);
        }
    }

    //Editar tag
    public function editarTag(Request $request, $id){
        try {
            $tag = Tags::where("id", $id)->first();


            $tag->nome = $request->nome ?? $tag->nome;
            $tag->url = $request->url ?? $tag->url;

            $tag->save();

            return response()->json([
                "message" => "Dados atualizados com sucesso",
                "fresh" => $post
            ]);

        } catch (Exception $e) {
            return response()->json([
                "error" => $e->getMessage()
            ], 500);
        }
    }

    //Deletar tag
    public function excluir($id){
        $res = Tags::where('id', $id)->delete();

        if ($res) {
            $data = [
                'status' => 'success',
                'msg' => 'Tag excluida com sucesso'
            ];
        } else {
            $data = [
                'status' => 'error',
                'msg' => 'Falha em excluir a tag'
            ];
        }
        return response()->json($data);
    }
    
    //Tag
    public function tag($id){
        return Tags::findOrfail($id);
    }
}
