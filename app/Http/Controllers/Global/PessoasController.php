<?php

namespace App\Http\Controllers\Global;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\{
    Pessoas,
    PessoasEndereco
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

class PessoasController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */


    public function __construct()
    {
        $this->middleware('auth:pessoas', [
            'except' =>
                ['entrar', 'cadastro', 'listar', "listarPaginada", "cadastrarEndereco", "enviarLinkDeRecupecaoDeSenha"]
        ]);
    }

    /**
     * Obtem o cliente pelo seu id
     */

    public function info(Request $request, $id)
    {
        $cliente = PessoasEndereco::where("id_pessoa", $id)->first();

        $enderecos = PessoasEndereco::where("id_pessoa", $id)->get();

        return response()->json([
            "dados_pessoais" => $cliente,
            "enderecos" => $enderecos
        ]);
    }
    /**
     * Lista de Pessoas.
     *
     */
    public function listarPaginada(Request $request)
    {
        if ($request->query("with_partners") == true) {
            return Pessoas::where("is_client", true, )->where("is_partner", true)->paginate($request->perPage ?? "10");
        }

        if ($request->query("with_afiliados") == true) {
            return Pessoas::where("is_client", true, )->where("is_afiliado", true)->paginate($request->perPage ?? "10");
        }

        return Pessoas::where("is_client", true)->paginate($request->perPage ?? "10");
    }

    public function listar(Request $request)
    {

        if ($request->query("with_partners") == true) {
            return Pessoas::where("is_partner", true, )->where("is_afiliado", true)->get();
        }

        if ($request->query("with_afiliados") == true) {
            return Pessoas::where("is_client", true, )->where("is_afiliado", true)->get();
        }

        $pessoas = Pessoas::where("is_client", true, )->get();

        return response()->json($pessoas);
    }
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function entrar(Request $request)
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

        $pessoas_checks = Pessoas::whereEmail($request->email)->first();


        if (!$pessoas_checks) {
            return response()->json(['error' => 'Email não encontrado'], 401);
        }

        if (!Hash::check($request->senha, $pessoas_checks->senha)) {
            return response()->json(['error' => 'senha invalida'], 401);
        }
        $token = auth('pessoas')->attempt(['email' => $request->email, 'password' => $request->senha]);

        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Acesso negado',
            ], 401);
        }

        $pessoa = auth('pessoas')->user();
        return response()->json([
            'status' => 'success',
            'pessoa' => $pessoa,
            'authorization' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }

    /**
     * Cadastro a pessoa.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function cadastro(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_pai' => 'nullable|integer|min:1|between:1,10',
            'cadastro_ativo' => 'integer|min:1',
            'codigo' => 'string|between:1,100',
            'nome' => 'string|between:1,100',
            'alias' => 'string|between:1,100',
            'email' => 'string|email|max:50|unique:pessoas',
            'signature_email' => 'string|email|max:50|unique:pessoas',
            'situacao' => 'string|between:1,100',
            'cpfcnpj' => 'unique:pessoas|string|min:1|between:1,20',
            'telefone' => 'string|between:1,100',
            'celular' => 'string|between:1,100',
            'razao_social' => 'nullable|string|between:1,100',
            'tipo' => 'nullable|string|min:1',
            'cpf_responsavel' => 'string|min:1|between:1,20',
            'foto' => 'nullable|string|between:1,100',
            'ie' => 'nullable|string|between:1,100',
            'data_nasc' => 'nullable|string|between:1,100',
            'sexo' => 'nullable|string|min:1',
            'rg' => 'nullable|string|between:1,100',
            'orgao_emissor' => 'nullable|string|between:1,100',
            'estado_civil' => 'nullable|string|between:1,100',
            'obs' => 'nullable|string|between:2,100',
            'cod_rec' => 'nullable|string|between:1,100',
            'id_endereco_fiscal' => 'nullable|integer|min:1|between:1,10',
            'id_clinte' => 'nullable|integer|min:1|between:1,10',
            'signature_pwd' => 'nullable|string|between:1,100',
            'senha' => 'string|between:1,100',
            'comiss_elegivel' => 'nullable|integer|min:1',
            'ccm' => 'nullable|string|between:1,100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "errors" => $validator->errors()
            ], 400);
        }

        try {
            $pessoa = Pessoas::create(
                array_merge(
                    $request->all(),
                    [
                        'senha' => Hash::make($request->senha),
                        "nome" => $request->razao_social ? $request->razao_social : $request->nome,
                        "is_client" => true
                    ]
                )
            )->fresh();

            return response()->json([
                'message' => 'Usuario registrado com sucesso',
                'pessoa' => $pessoa
            ], 200);
        } catch (Exception $e) {
            return response()->json(["e" => $e->getMessage()], 400);
        }
    }


    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function atualizarToken(Request $request)
    {
        try {
            return auth('pessoas')->refresh();

        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json([
                "error" => $e->getMessage(),
                "token" => $request->header("Authorization")
            ]);
        }
    }

    /**
     * Get the authenticated pessoa.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function pessoaPerfil()
    {

        return response()->json(auth('pessoas')->user());
    }

    /**
     * funçao de derrubar(sair) o usuário.
     *
     */
    public function sair(Request $request)
    {

        auth('pessoas')->logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Desconectado',
        ], 200);
    }


    /**
     * Edita os dados do cliente
     */
    public function editarDadosCliente(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->only("email", "cpfcnpj", "rg"), [
                "email" => "email|unique:pessoas",
                "rg" => "unique:pessoas",
                "cpfcnpj" => "unique:pessoas"
            ]);


            if ($validator->fails()) {
                return response()->json([
                    "errors" => $validator->errors()
                ], 400);
            }

            $cliente = Pessoas::where("id", $id)->first();

            if (!$cliente || $cliente == null) {
                return response()->json([
                    "errors" => [
                        "INDEX_DOES_NOT_EXISTS" => "O ídice fornecido não existe ou está ínvalido."
                    ]
                ]);
            }

            $keys = [];

            foreach ($request->input() as $key => $value) {
                array_push($keys, $key);
            }

            foreach ($keys as $k) {
                $cliente->update([
                    $k => $request->$k ?? $cliente->$k
                ]);
            }

            $cliente->save();

            return response()->json([
                "message" => "Dados atualizados com sucesso",
                "fresh" => $cliente,
            ]);
        } catch (Exception $e) {
            return response()->json([
                "error" => $e->getMessage()
            ], 500);
        }
    }

    public static function atualizarSenha(Request $request, $id){
        try {

           $cliente = Pessoas::where("id", $id)->first();

            if (password_verify($request->senha_atual, $cliente->senha)) {
                $resposta = "Senha atualizada com sucesso.";
                $cliente->senha = Hash::make($request->senha);
                $cliente->save();
                return response()->json([
                    'status' => 'success',
                    'message' => $resposta,
                ], 200);
            }else{
                return response()->json([
                    'status' => 'error',
                    'message' => 'Senha atual invalida',
                ], 400);
            }
           
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => "Erro no servidor",
            ], 500);
        }
    }

}