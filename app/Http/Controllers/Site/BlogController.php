<?php

namespace App\Http\Controllers\Site;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\{
    Hash,
    Validator
};
use App\Models\{
    Posts,
    Tags
};
use Exception;

class BlogController extends Controller
{
    //Lista de post do blog
    public function listar(){
        $posts = Posts::paginate(10);

        return response()->json($posts);
    }

    //Detalhes do post
    public function post(Request $request, $id){
        return Posts::findOrfail($id);
    }

    //Cadastra post
    public function cadastraPost(Request $request){
        $validator = validator::make($request->all(), [
            "titulo" => "required|string",
            "descricao" => "nullable|string",
            "keywords" => "nullable|string",
            "seo_descricao" => "nullable|string",
            "capa" => "nullabe|string",
            "url" => "nullabe|string",
        ]);

        if ($validator->fails()) {
            return response()->json(["errors" => $validator->errors()], 400);
        }

        try {
            Posts::create($request->all());

            return response()->json([
                "status" => "success",
                "message" => "Post cadastrado com sucesso."
            ]);
        } catch (Exception $e) {
            return response()->json([
                "status" => "error",
                "message" => "Desculpe estamos com problemas",
                "error" => $e->getMessage()
            ], 500);
        }
    }

    //Editar post
    public function editarPost(Request $request, $id){
        try {
            $post = Posts::where("id", $id)->first();


            $post->titulo = $request->titulo ?? $post->titulo;
            $post->descricao = $request->descricao ?? $post->descricao;
            $post->keywords = $request->keywords ?? $post->keywords;
            $post->seo_descricao = $request->seo_descricao ?? $post->seo_descricao;
            $post->capa = $request->capa ?? $post->capa;
            $post->url = $request->url ?? $post->url;

            $post->save();

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

    //Deletar post
    public function excluir($id){
        $res = Posts::where('id', $id)->delete();

        if ($res) {
            $data = [
                'status' => 'success',
                'msg' => 'Post excluida com sucesso'
            ];
        } else {
            $data = [
                'status' => 'error',
                'msg' => 'Falha em excluir o post'
            ];
        }
        return response()->json($data);
    }
}
