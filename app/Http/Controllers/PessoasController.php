<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Models\{
    Pessoas,
    PessoasEndereco
};
use DateTime;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\{Hash, Validator};

class PessoasController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:pessoas', ['except' => ['entrar', 'cadastro', 'listar', "listarPaginada", "cadastrarEndereco", "info"]]);
    }


     /**
     * Lista de Pessoas em páginas.
     *
     */
    public function listarPaginada(Request $request)
    {
        return Pessoas::where("is_client", true)->paginate($request->perPage ?? "10");
    }

    /**
     * Lista de Pessoas.
     *
     */
    public function listar(Request $request)
    {
        $pessoas = Pessoas::where("is_client", true)->get();

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
            'cpfcnpj' => 'string|min:1|between:1,20',
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

        //dd($validator);
        if ($validator->fails()) {
            return response()->json([
                "errors" => $validator->errors()
            ], 400);
        }

        try {
            $pessoa = Pessoas::create(array_merge(
                $request->all(),
                ['senha' => bcrypt($request->senha)]
            ))->fresh();

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
    public function atualizarToken()
    {
        return auth('pessoas')->refresh();
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
     * Informação individual de pessoa
     *
     */
    public function info($id)
    {
        return Pessoas::findOrfail($id);
    }

    /**
     * Editar Cliente
     *
     */
    public function editarPessoa(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->only("email", "cpfcnpj", "rg"), [
                "email" => "email|unique:pessoas",
                "rg" => "unique|pessoas",
                "cpfcnpj" => "unique:pessoas"
            ]);

            if($validator->fails()) {
                return response()->json([
                    "errors" => $validator->errors()
                ], 400);
            } 

            $cliente = Pessoas::where("id", $id)->first();
           
            if(!$cliente || $cliente == null) {
                return response()->json([
                    "errors" => [
                        "INDEX_DOES_NOT_EXISTS" => "O ídice fornecido não existe ou está ínvalido."
                    ]
                ]);
            }


            $cliente->nome = $request->nome ?? $cliente->nome;
            $cliente->id_pai = $request->id_pai ?? $cliente->id_pai;
            $cliente->alias = $request->alias ?? $cliente->alias;
            $cliente->email = $request->email ?? $cliente->email;
            $cliente->codigo = $request->codigo ?? $cliente->codigo;
            $cliente->razao_social = $request->razao_social ?? $cliente->razao_social;
            $cliente->signature_email = $request->signature_email ?? $cliente->signature_email;
            $cliente->situacao = $request->situacao ?? $cliente->situacao;
            $cliente->cpfcnpj = $request->cpfcnpj ?? $cliente->cpfcnpj;
            $cliente->telefone = $request->telefone ?? $cliente->telefone;
            $cliente->celular = $request->celular ?? $cliente->celular;
            $cliente->cpf_responsavel = $request->cpf_responsavel ?? $cliente->cpf_responsavel;
            $cliente->foto = $request->foto ?? $cliente->foto;
            $cliente->ie = $request->ie ?? $cliente->ie;
            $cliente->data_nasc = $request->data_nasc ?? $cliente->data_nasc;
            $cliente->sexo = $request->sexo ?? $cliente->sexo;
            $cliente->rg = $request->rg ?? $cliente->rg;
            $cliente->orgao_emissor = $request->orgao_emissor ?? $cliente->orgao_emissor;
            $cliente->estado_civil = $request->estado_civil ?? $cliente->estado_civil;
            $cliente->obs = $request->obs ?? $cliente->obs;
            $cliente->cod_rec = $request->cod_rec ?? $cliente->cod_rec;
            $cliente->id_endereco_fiscal = $request->id_endereco_fiscal ?? $request->id_endereco_fiscal;
            $cliente->signature_pwd = $request->signature_pwd  ?? $cliente->signature_pwd;
            $cliente->comiss_elegivel = $request->comiss_elegivel ?? $cliente->comiss_elegivel;
            $cliente->ccm = $request->ccm ?? $cliente->ccm;

            $cliente->save();

            return response()->json([
                "message" => "Dados atualizados com sucesso",
                "fresh" => $cliente
            ]);

        } catch(Exception $e) {
            return response()->json([
                "error" => $e->getMessage()
            ], 500);
        }
    }
}
