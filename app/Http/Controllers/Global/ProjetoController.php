<?php

namespace App\Http\Controllers\Global;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Projetos;
use Illuminate\Support\Str;

class ProjetoController extends Controller
{

    public function obterProjeto(Request $request, $id_pessoa, $nome_projeto)
    {
        try {
            $projeto = Projetos::where("id_pessoa", $id_pessoa)
                ->where("nome_projeto", $nome_projeto)
                ->first();

            if (!$projeto || $projeto == null) {
                return response()->json([
                    "message" => "Projeto não econtrado"
                ], 422);
            }

        } catch (\Exception $e) {
            return response()->json([
                "message" => "Erro interno"

            ], 500);
        }
    }

    public function lerProjetos(Request $request, $id)
    {
        try {
            $projetos = Projetos::where("id_pessoa", $id)->get();

            if ($projetos->count() == 0) {
                return response()->json([
                    "is_found" => false,
                    "message" => "Projetos não econtrado"
                ], 422);
            }

            return response()->json([
                "projetos" => $projetos
            ]);

        } catch (\Exception $e) {
            return response()->json([
                "message" => "Erro interno"

            ], 500);
        }
    }

    public function salvarProjeto(Request $request)
    {
        try {
            $projeto = Projetos::where("id_pessoa", $request->id)
                ->where("nome_projeto", $request->nome_projeto)
                ->first();

           /*  return response()->json([
                $request->input()
            ], 500); */

            if ($projeto) {
                $projeto->canvas_json = $request->canvas_json;

                $projeto->save();

                return response()->json([
                    "message" => "Projeto salvo",
                    "fresh_project" => $projeto
                ]);
            }

            $dados_projeto = [
                "projeto_uuid" => Str::ulid(),
                "nome_projeto" => $request->nome_projeto,
                "canvas_json" => $request->canvas_json,
                "id_pessoa" => $request->id
            ];

            $project = Projetos::create($dados_projeto)->fresh();

            return response()->json([
                "fresh_project" => $project,
                "message" => "Projeto salvo"
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                "message" => $e->getMessage()
            ], 500);
        }
    }
}
