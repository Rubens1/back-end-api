<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Models\{
    Pessoas,
    PessoasEndereco,
    PessoasGrupos,
    RecuperarSenha,
    Grupos
};
use DateTime;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\{Hash, Validator};
use App\Helpers\Logger;
use App\Jobs\SendColaboradorSenhaEmailQueue;
use App\Mail\SendColaboradorSenha;
use Illuminate\Support\Facades\Mail;

class ColaboradorController extends Controller
{


    /**
     * Cadastra um novo colaborador
     */
    public function cadastrarColaborador(Request $request)
    {
        try {
            $group_errors = [];

            $validator = validator::make($request->all(), [
                "nome" => "required|string",
                "email" => "unique:pessoas"
            ]);

            $grupos = explode(",", trim($request->grupo));
            
            if ($validator->fails()) {
                return response()->json([
                    "status" => "error",
                    "error_type" => "validation",
                    "errors" => $validator->errors()
                ], 422);
            }

            $colaborador = Pessoas::create([
                "email" => $request->email,
                "nome" => $request->nome,
                "verificou_senha" => false,
                "senha" => Hash::make("akad-123456"),//Senha padrão
                "is_client" => false
            ])->fresh();

            foreach ($grupos as $index) {
                $grupo = Grupos::where("id", $index)->first();

                if($grupo == "") {
                    continue;
                }

                if (!$grupo || $grupo == null) {
                    array_push($group_errors, "O indice $index é invalido revise eese indice");
                }

                $grupo = PessoasGrupos::create([
                    "id_pessoa" => $colaborador->id,
                    "id_grupo" => $index
                ])->fresh();
            }

            $token = Str::uuid();
            $date_time = new DateTime();
            $interval = new \DateInterval('PT24H'); //Link para o colabarodar cadastrar a senha expira em 24horas

            $date_time->add($interval);

            RecuperarSenha::create([
                "id_pessoa" => $colaborador->id,
                "uuid" => $token,
                "expira_em" => $date_time->format("d-m-Y H:i")
            ]);

            $link = "http://localhost:3000/cadastrar-senha/$token";

            SendColaboradorSenhaEmailQueue::dispatchAfterResponse(
                $colaborador->email,
                $colaborador->nome,
                $link
            );

            return response()->json([
                "status" => "success",
                "message" =>
                    "Colaborador cadastrado com sucesso, peça para o mesmo verficar o Email e cadastrar uma senha",
                "obs" => $group_errors
            ]);


        } catch (Exception $e) {

            return response()->json([
                "status" => "Error",
                "message" => "Desculpe estamos com problemas",
                "error" => $e->getMessage()
            ], 500);
        }
    }

}
