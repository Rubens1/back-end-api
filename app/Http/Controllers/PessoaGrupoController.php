<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PessoaGrupo;
use Illuminate\Support\Facades\{Hash, Validator};
use Exception;

class PessoaGrupoController extends Controller
{
    /**
     * Lista todos os permição das pessoas
     */
    public function listar()
    {
        return PessoaGrupo::all();
    }

    /**
     * Cadastra Grupo
     */
    public function cadastrarColaborador(Request $request)
    {
        $validator = validator::make($request->all(), [
            'id_grupo' => 'nullable|integer',
            'sessao' => 'required|string',
            'array_permissoes' => 'string',
            'id_pessoa' => 'required|integer'
        ]);
    
        if ($validator->fails()) {
            return response()->json(["errors" => $validator->errors()], 400);
        }
        try {
            PessoaGrupo::create($request->all());
        
        return response()->json([
            "status" => "SUCCESS",
            "message" => "Permição adicionada"
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
     * Lsita de Permição da pessoa
     */
    public function permissao(Request $request)
    {
        $result = PessoaGrupo::where('id_pessoa', $request->id_pessoa)
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
        /* return PessoaGrupo::where('id_pessoa', $request->id_pessoa)
        ->join('pessoas', 'pessoas.id', '=', 'pessoa_grupos.id_pessoa')
        ->select('pessoa_grupos.*', 'pessoas.nome')
        ->get(); */
    }

}
