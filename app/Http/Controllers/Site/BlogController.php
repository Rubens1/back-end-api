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
    Tags,
    Blog
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
    public function post($url){
        return response()->json(Posts::where('url', $url)->first());
    }

    //Cadastra post
    public function cadastraPost(Request $request){
        $validator = validator::make($request->all(), [
            "titulo" => "required|string",
            "descricao" => "string",
            "keywords" => "string",
            "seo_descricao" => "string",
            "capa" => "string",
            "url" => "required|string",
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
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                "error" => $e->getMessage()
            ], 500);
        }
    }

    //Deletar post
    public function excluirPost($id){
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

    // Lista de tags vinculadas ao post
    public function tagsPost($id_post){
        $res = Blog::where('id_post', $id_post)
        ->leftJoin('tags', function ($join) {
            $join->on('tags.id', '=', \DB::raw("CAST(blog.id_tag AS SIGNED)"))
                ->orWhere('tags.id', '=', \DB::raw("SUBSTRING_INDEX(blog.id_tag, ',', -1)"));
        })
        ->select('tags.*')
        ->get();

        return response()->json($res);
    }

    //Cadastra post em alguma tag
    public function postTag(Request $request){
        $validator = validator::make($request->all(), [
            "id_post" => "required|integer",
            "id_tag" => "required|string",
            "keywords" => "nullable|string",
            "seo_descricao" => "nullable|string",
            "url" => "required|string",
        ]);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }
        

        try {
            Blog::create($request->all());
            return response()->json([
                "status" => "success",
                "msg" => "Post vinculado com tag com sucesso"
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                "error" => $e->getMessage()
            ], 500);
        }
    }

    //Lista post por tag
    public function listaPostTag($id_tag){
        $res = Blog::where('id_tag', 'like', "%{$id_tag}%")
        ->leftJoin("posts", "posts.id", "=", "blog.id_post")
        ->paginate(10);

    return response()->json($res);
    }

    //Pesquisa post
    public function pesquisaPost(Request $request){
        $res = Posts::where('titulo', 'like', "%{$request->titulo}%")
        ->paginate(10);

    return response()->json($res);
    }

     //Pesquisa post co tag
     public function pesquisaPostTag(Request $request){
        $res = Posts::where('posts.titulo', 'like', "%{$request->titulo}%", 'AND', 'blog.id_tags', 'like', "%{$request->id_tag}%")
        ->leftJoin("blog", "blog.id_post", "=", "posts.id")
        ->rightJoin("tags", "tags.id", "=", "blog.id_tag")
        ->select('posts.*', 'tags.id AS idTag', 'blog.id_tag', 'blog.id_post')
        ->paginate(10);

    return response()->json($res);
    }
}