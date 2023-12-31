<?php

namespace App\Http\Controllers\Client;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\{
    Pessoas,
    PessoasEndereco
};
use DateTime;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\{Hash, Validator};

class EnderecoController extends Controller
{
    public function cadastrarEndereco(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'filial' => 'nullable|string|max:60',
            'identificacao' => "required|string",
            'logradouro' => 'required|string|max:100',
            'numero' => 'nullable|string|max:20',
            'complemento' => 'nullable|string|max:45',
            'bairro' => 'nullable|string|max:50',
            'cep' => 'required|string|max:10',
            'cidade' => 'required|string|max:50',
            'cod_municipio' => 'nullable|string|max:10',
            'estado' => 'required|string|size:2',
            'responsavel' => 'required|string|max:60',
            'rg_responsavel' => 'nullable|string|max:10',
            'ip_cadastro' => 'nullable|ipv4',
            'obs' => 'nullable|string',
            'active' => 'integer|in:0,1',
            'favorite' => 'integer|in:0,1',
            'id_usuario' => 'integer|nullable',
            'cod_uf' => 'integer|nullable',
            'fone' => 'nullable|string|max:254',
            'retirada' => 'string|size:8',
            'impressao' => 'string|size:8',
            'material' => 'string|size:8',
            'venda' => 'string|size:8',
            'layout' => 'string|size:8',
            'faz_entrega' => 'string|size:8',
            'tx_entrega' => 'numeric|nullable',
            'km_lim_entrega' => 'integer',
            'foto' => 'nullable|string|max:255',
            'recebe_dinheiro' => 'string|size:8|nullable',
            'km_lim_entrega_ex' => 'integer|nullable',
            'tx_entrega_ex' => 'numeric|nullable',
            'referencia' => 'nullable|string|max:255',
            'id_matriz' => 'integer|nullable',
            'faz_entrega_ex' => 'string|size:8',
            'principal' => 'boolean',
            'id_pessoa' => 'required|exists:pessoas,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "errors" => $validator->errors()
            ], 400);
        }

        try {
            //return response()->json($request->input());
            $endereco = PessoasEndereco::create(
                array_merge(
                    $validator->validated(),
                    [
                        "situacao" => "Ativo",
                        //"principal" => false
                    ]
                )
            )->fresh();

            return response()->json([
                "status" => "success",
                "message" => "Endereço cadastrado",
                "endereco" => $endereco
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                "status" => "error",
                "message" => "Desculpe estamos enfrentado problemas internos.",
                "error" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtem os enderecos do cliente pelo id do cliente 
     */
    public function obterListaDeEnderecosDoCliente(Request $request, $id)
    {
        try {

            $enderecos = PessoasEndereco::where("id_pessoa", $id)->get();

            if($enderecos->count() == 0) {
                return response()->json([
                    "status" => "error",
                    "message" => "Usuário não possui nenhum endereço cadastrado."
                ], 400);
            }

            return response()->json([
                "enderecos" => $enderecos,
                "quantidade" => $enderecos->count()
            ]);

        } catch (Exception $e) {
            return response([
                "error" => "Erro"
            ], 500);
        }
    }

    /**
     * Obtem o endereco do cliente pelo Id
     */
    public function obterEnderecoPeloId(Request $request, $id)
    {
        $endereco = PessoasEndereco::where("id", $id)->first();

        return response()->json($endereco);
    }

    /**
     * Dados endereço pelo id do endereço
     */
    public function editarEndereco(Request $request, $id)
    {
        try {

            if (count($request->input()) == 0) {
                return response()->json([
                    "status" => "ERROR",
                    "message" => "Para atualizar algum campo no tabela de endereço passe pelo um parametro válido"
                ], 400);
            }

            $endereco = PessoasEndereco::where("id", $id)->first();

            $keys = [];

            foreach ($request->input() as $key => $value) {
                array_push($keys, $key);
            }

            foreach ($keys as $key) {
                $endereco->update([
                    $key => $request->$key ?? $endereco->$key
                ]);
            }

            $endereco->save();

            return response()->json([
                "message" => "Dados de endereço atualizado com sucesso.",
                "fresh" => $endereco
            ]);

        } catch (Exception $e) {
            return response()->json([
                "error" => $e->getMessage(),
                "message" => "Erro interno tente mais tarde"
            ], 500);
        }
    }

    /**
     * Deletar registro pelo id
     */
    public function excluir($id)
    {
        $res = PessoasEndereco::where('id', $id)->delete();

        if ($res) {
            $data = [
                'status' => '1',
                'msg' => 'Endereco excluida com sucesso'
            ];
        } else {
            $data = [
                'status' => '0',
                'msg' => 'Falha em excluir o endereco'
            ];
        }
        return response()->json($data);
    }

    /**
     * Desativar endereço pelo id do endereço
     */
    public function desativaEndereco(Request $request, $id)
    {
        try {

            if (count($request->input()) == 0) {
                return response()->json([
                    "status" => "ERROR",
                    "message" => "Não foi possivel excluir o edereço no momento"
                ], 400);
            }

            $endereco = PessoasEndereco::where("id", $id)->first();

            $endereco->situacao = $request->situacao ?? $endereco->situacao;

            $endereco->save();

            return response()->json([
                "message" => "Enderecço excluido com sucesso.",
                "fresh" => $endereco
            ]);

        } catch (Exception $e) {
            return response()->json([
                "error" => $e->getMessage(),
                "message" => "Erro interno tente mais tarde"
            ], 500);
        }
    }

    /**
     * Endereço principal pelo id
     */
    public function principalEndereco(Request $request, $id, $id_pessoa)
    {
        // Atualize todos os registros para '0' onde o ID da pessoa é igual a $id_pessoa
        PessoasEndereco::where('id_pessoa', $id_pessoa)->update(['principal' => 0]);

        // Busque o registro pelo ID e ID da pessoa
        $registro = PessoasEndereco::where('id', $id)->where('id_pessoa', $id_pessoa)->first();

        if (!$registro) {
            return response()->json(['success' => false, 'error' => 'Registro não encontrado'], 404);
        }

        // Atualize o registro específico para '1'
        $registro->update(['principal' => 1]);

        return response()->json(['success' => true, 'message' => 'Status atualizado com sucesso']);
    }
}