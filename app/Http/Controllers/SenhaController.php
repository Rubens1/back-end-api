<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\{
    RecuperarSenha,
    Pessoas
};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Hash, Validator};
use Illuminate\Support\Facades\Http;
use App\Mail\SendPasswordMail;
use App\Jobs\{
    RecuperSenhaQueue,
    SendEmailConfirmationEmailQueue
};
use Exception;
use Illuminate\Support\Str;


class SenhaController extends Controller
{
    function verificarTokenDeRecuperacaoDeSenha(Request $request)
    {
        try {
            $valid_token = RecuperarSenha::where("uuid", $request->token)->first();

            if (!$valid_token || $valid_token == null) {
                return response()->json([
                    "status" => "error",
                    "message" => "Link invalído",
                    "is_valid" => false
                ]);
            }

            $current_time = new \DateTime();
            $token_datetime = new \DateTime($valid_token->expira_em);

            if ($current_time->getTimestamp() > $token_datetime->getTimestamp()) {
                return response()->json([
                    "message" => "Link expirado",
                    "is_valid" => false
                ]);
            }

            return response()->json([
                "is_valid" => true
            ]);

        } catch (Exception $e) {

            return response()->json([
                "is_valid" => false,
                "message" => "Erro interno"
            ], 401);

        }
    }
    public function enviarLinkDeRecupecaoDeSenha(Request $request)
    {
        try {
            $cliente = Pessoas::where("email", $request->email)->first();

            if (!$cliente || $cliente == null) {
                return response()->json([
                    "status" => "ERROR",
                    "message" => "Email não encontrado"
                ], 422);
            }

            $token = Str::uuid();
            $date_time = new \DateTime();
            $interval = new \DateInterval('PT1H'); //Link para recuper a senha dura duas horas 

            $date_time->add($interval);

            RecuperarSenha::create([
                "id_pessoa" => $cliente->id,
                "uuid" => $token,
                "expira_em" => $date_time->format("d-m-Y H:i")
            ]);

            $link = "http://localhost:3000/recuperar-senha/$token";

            RecuperSenhaQueue::dispatchAfterResponse(
                $cliente->email,
                explode(" ", $cliente->nome)[0],
                $link
            );

            return response()->json([
                "message" => "Link de recupeção de senha enviado"
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                "status" => "error",
                "e" => $e->getMessage()
            ], 500);
        }
    }
    public function recuperarSenha(Request $request)
    {
        try {
            $valid_token = RecuperarSenha::where("uuid", $request->token)->first();

            if (!$valid_token || $valid_token == null) {
                return response()->json([
                    "token_status" => "DOES_NOT_EXIS TS",
                    "message" => "Token expirado",
                    "t" => $request->token,
                    "s" => $request->senha
                ], 400);
            }

            $cliente = Pessoas::where("id", $valid_token->id_pessoa)->first();

            $cliente->senha = Hash::make($request->senha);

            $cliente->save();

            $valid_token->delete();

            SendEmailConfirmationEmailQueue::dispatchAfterResponse($cliente->email);

            return response()->json([
                "message" => "Senha alterada com sucesso"
            ]);

        } catch (Exception $e) {
            return response()->json([
                "status" => "error",
                "message" => "Erro interno"
            ]);

        }
    }
}
