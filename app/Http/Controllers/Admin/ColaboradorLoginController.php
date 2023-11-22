<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\{
    Pessoas,
    PessoasEndereco,
    PessoasGrupos
};
use DateTime;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\{Hash, Validator};
use App\Helpers\Logger;
use App\Models\RecuperarSenha;
use App\Mail\SendPasswordMail;
use App\Jobs\RecuperSenhaQueue;

class ColaboradorLoginController extends Controller
{
   
    public function listar()
    {
        $pessoas = Pessoas::where("is_client", false)->get();

        return response()->json($pessoas);
    }
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {

        $validador = Validator::make(
            $request->only('email', 'senha'),
            [
                'email' => 'required|string|email',
                'senha' => 'required|string',
            ]
        );

        if ($validador->fails()) {
            return response()->json($validador->errors(), 400);
        }

        $colaborador = Pessoas::where("email", $request->email)->first();

        if (!$colaborador) {
            return response()->json(['error' => 'Email não encontrado'], 401);
        }
        
        if($colaborador->is_cliente == true){
            return response()->json([
                "message" => "Ambiente não permitido"
            ], 401);
        }


        if (!Hash::check($request->senha, $colaborador->senha)) {
            return response()->json(['error' => 'senha invalida'], 401);
        }

        $token = auth('pessoas')->attempt(['email' => $request->email, 'password' => $request->senha]);

        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Acesso negado',
            ], 401);
        }

        $permissoes = PessoasGrupos::where("id_pessoa", $colaborador->id)->first();

        $pessoa = auth('pessoas')->user();

        return response()->json([
            'status' => 'success',
            'pessoa' => $pessoa,
            'authorization' => [
                'permissoes' => $permissoes,
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }

}
