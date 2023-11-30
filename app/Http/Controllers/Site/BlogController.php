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
    Blog,
    Tags,
    Pessoas
};

class BlogController extends Controller
{
    //Lista de postagem
    public function listaPosts(){
        return Posts::all();
    }

    //Lista de tags
    public function listaTags(){
        return Tags::all();
    }

    //Cadastra Post
    public function cadastraPost(Request $request){
        $validator = validator::make($request->all(), [
            'titulo' => 'required|string',
            'descricao' => 'required|string',
            'keywords' => 'required|string',
            'seo_descricao' => 'required|string',
            'capa' => 'required|string',
            "id_pessoa" => "required|exists:pessoas,id",
            'url' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(["errors" => $validator->errors()], 400);
        }

        try {
            
            Posts::create($request->all());

            return response()->json([
                "status" => "success",
                "message" => "Post publicado com sucesso"
            ]);
        } catch (Exception $e) {
            return response()->json([
                "status" => "error",
                "message" => "Desculpe estamos com problemas",
                "error" => $e->getMessage()
            ], 500);
        }
    }

    //Cadastra tag
    public function cadastraTag(Request $request){
        $validator = validator::make($request->all(), [
            'nome' => 'required|string|unique:tags',
            'url' => 'required|string|unique:tags'
        ]);

        if ($validator->fails()) {
            return response()->json(["errors" => $validator->errors()], 400);
        }

        try {

            Tags::create($request->all());
            
            return response()->json([
                "status" => "success",
                "message" => "Tag cadastrado com sucesso"
            ]);
        } catch (Exception $e) {
            return response()->json([
                "status" => "error",
                "message" => "Desculpe estamos com problemas",
                "error" => $e->getMessage()
            ], 500);
        }
    }

    //Editar Post
    public function editarPost(Request $request, $id){
        try{
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
                "data" => $post
            ],200);

        } catch (Exception $e) {
            return response()->json([
                "error" => $e->getMessage()
            ],500);
        }
    }

    //Editar tag
    public function editarTag(Request $request, $id){
        try{
            $tag = Tags::where("id", $id)->first();


            $tag->nome = $request->nome ?? $tag->nome;
            $tag->url = $request->url ?? $tag->url;
        
            $tag->save();

            return response()->json([
                "message" => "Dados atualizados com sucesso",
                "data" => $tag
            ],200);

        } catch (Exception $e) {
            return response()->json([
                "error" => $e->getMessage()
            ],500);
        }
    }

    //Excluir Post
    public function excluirPost($id){
        $res = Posts::where('id', $id)->delete();
        Blog::where('id_post', $id)->delete();
        if ($res) {
            $data = [
                'status' => 'success',
                'msg' => 'Post excluida com sucesso'
            ];
        } else {
            $data = [
                'status' => 'error',
                'msg' => 'Falha em excluir o Post'
            ];
        }
        return response()->json($data);
    }

    //Excluir Tag
    public function excluirTag($id){
        $res = Tags::where('id', $id)->delete();

        if ($res) {
            $data = [
                'status' => 'success',
                'msg' => 'Tag excluida com sucesso'
            ];
        } else {
            $data = [
                'status' => 'error',
                'msg' => 'Falha em excluir a Tag'
            ];
        }
        return response()->json($data);
    }

    //Info do post
    public function infoPost($id){
        return Posts::findOrfail($id);
    }

    //Info da tag
    public function infoTag($id){
        return Tags::findOrfail($id);
    }

    //Meus posts
    public function meusPosts($id_pessoa){
        return Posts::where("id_pessoa",$id_pessoa);
    }

    //Blog com post e tag
    public function blogVinculo(){
        $validator = validator::make($request->all(), [
            'id_post' => 'required|integer',
            'id_tag' => 'integer',
            "view" => 'integer',
            "keywords" => 'string',
            "seo_descricao" => 'string',
            "url" => 'string'
        ]);

        if ($validator->fails()) {
            return response()->json(["errors" => $validator->errors()], 400);
        }

        try {

            Blog::create($request->all());
            
            return response()->json([
                "status" => "success",
                "message" => "Post vinculado as tags com sucesso"
            ]);
        } catch (Exception $e) {
            return response()->json([
                "status" => "error",
                "message" => "Desculpe estamos com problemas",
                "error" => $e->getMessage()
            ], 500);
        }
    }
}
