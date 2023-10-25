<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{PessoaGrupos, Pessoas};
use Illuminate\Support\Facades\{Hash, Validator};
use Exception;

class PessoaGrupoController extends Controller
{


    /**
     * Lista todos os permição das pessoas
     */
    public function listar()
    {
        return PessoaGrupos::all();
    }

    /**
     * Cadastra Grupo
     */
    public function cadastrarPermissao(Request $request)
    {
        try {
            $info = [
                'nome' => $request->nome,
                'email' => $request->email,
                'senha' => $request->senha,
                'sessao' => $request->sessao ?? null,
                'array_permissoes' => $request->array_permissoes ?? null,
                'id_grupo' => $request->id_grupo ?? null,
            ];
            $pessoas = Pessoas::where("email", $request->email)
            ->first();

            if($pessoas){
                return response()->json([
                    "status" => "Error",
                    "message" => "Email já cadastrado"
                ], 401);
            }

            $pessoa = Pessoas::create([
                "nome" => $info["nome"],
                "email" => $info["email"],
                "senha" => bcrypt($info["senha"])
            ])->fresh();

            $merged = [...$info, "id_pessoa" => $pessoa->id];
            PessoaGrupos::create($merged);
        
            return response()->json([
                "status" => "Success",
                "message" => "Colaborador cadastrado com sucesso"
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
     * Lsita de Permição da pessoa
     */
    public function permissao(Request $request)
    {
        $result = PessoaGrupos::where('id_pessoa', $request->id_pessoa)
        ->join('pessoas', 'pessoas.id', '=', 'pessoa_grupos.id_pessoa')
        ->select('pessoa_grupos.sessao', 'pessoa_grupos.array_permissoes', 'pessoas.nome')
        ->first();

    if ($result) {
        $sessao = explode(",", $result->sessao);
        $array_permissoes = json_decode($result->array_permissoes);

        // Combine as informações em um único array associativo
        $combinedData = [];

        foreach ($sessao as $index => $item) {
            // Se a chave não existe no $combinedData, crie uma nova array
            if (!isset($combinedData[$item])) {
                $combinedData[$item] = [];
            }

            // Mesclar as permissões na array correspondente
            $combinedData[$item] = $array_permissoes[$index];
        }

        return response()->json([
            "Pessoa" => $result->nome,
            "Permição" => $combinedData
        ], 200);
    } else {
        // Lida com o caso em que não há resultados
        return response()->json(['message' => 'Nenhum resultado encontrado'], 404);
    }
        /* return PessoaGrupos::where('id_pessoa', $request->id_pessoa)
        ->join('pessoas', 'pessoas.id', '=', 'pessoa_grupos.id_pessoa')
        ->select('pessoa_grupos.*', 'pessoas.nome')
        ->get(); */
    }

}
