<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Models\Pessoa;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\{Hash, Validator};

class PessoaController extends Controller
{
   /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth:pessoa', ['except' => ['entrar', 'cadastro','lista']]);
    }

    /**
     * Lista de Pessoas.
     *
     */
    public function lista() {
        return Pessoa::all();
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function entrar(Request $request){
    	
        $validador = Validator::make($request->only('email','senha'),
        [
            'email' => 'required|string|email',
            'senha' => 'required|string',
        ]);

        if($validador->fails()){
            return response()->json($validador->errors(), 400);
        }

        $pessoa_checks = Pessoa::whereEmail($request->email)->first();
        
        
        if (!$pessoa_checks) {
            return response()->json(['error' => 'Email não encontrado'], 401);
        }
        
        if (!Hash::check($request->senha, $pessoa_checks->senha)) {
            return response()->json(['error' => 'senha invalida'], 401);
        }
        $token = auth('pessoa')->attempt(['email' => $request->email, 'password' => $request->senha]);
        
        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Acesso negado',
            ], 401);
        }
        
        $pessoa = auth('pessoa')->user();
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
    public function cadastro(Request $request) {
        $validator = Validator::make($request->all(), [
            'id_pai' => 'integer|min:1|between:1,10',
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
            'razao_social' => 'string|between:1,100',
            'tipo' => 'string|min:1',
            'cpf_responsavel' => 'string|min:1|between:1,20',
            'foto' => 'string|between:1,100',
            'ie' => 'string|between:1,100',
            'data_nasc' => 'string|between:1,100',
            'sexo' => 'string|min:1',
            'rg' => 'string|between:1,100',
            'orgao_emissor' => 'string|between:1,100',
            'estado_civil' => 'string|between:1,100',
            'obs' => 'string|between:2,100',
            'cod_rec' => 'string|between:1,100',
            'id_endereco_fiscal' => 'integer|min:1|between:1,10',
            'id_clinte' => 'integer|min:1|between:1,10',
            'signature_pwd' => 'string|between:1,100',
            'senha' => 'string|between:1,100',
            'comiss_elegivel' => 'integer|min:1',
            'ccm' => 'string|between:1,100',
        ]);
        
        //dd($validator);
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }
        
            $pessoa = Pessoa::create(array_merge(
                $validator->validated(),
                ['senha' => bcrypt($request->senha)]
            ));
            return response()->json([
                'message' => 'Usuario registrado com sucesso',
                'pessoa' => $pessoa
            ], 200);
    }

     /**
     * Get the authenticated pessoa.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function pessoaPerfil() {
        return response()->json(auth('pessoa')->user());
    }

    /**
     * funçao de derrubar(sair) o usuário.
     *
     */
    public function sair(Request $request){

        auth('pessoa')->logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Desconectado',
        ], 200);
    }
}
