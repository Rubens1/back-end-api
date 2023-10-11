<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\{
    Produtos,
    CategoriaProdutos,
    Estoque
};


use Illuminate\Support\Facades\{Hash, Validator};

class ProdutoController extends Controller
{
    /**
     * Lista de produto
     */
    public function lista()
    {

        $produtos = Estoque::with(["produto"])->paginate(10);
        

        return response()->json($produtos);
    }

    /**
     * Cadastra produto
     */
    public function cadastra(Request $request)
    {
        try {

            $validador = Validator::make($request->all(), [
                "sn" => "unique:produtos",
                'id_especializacao' => 'nullable|integer',
                'ativo' => 'integer',
                'nome' => 'string|unique:produtos',
                'src_alt' => 'nullable|string',
                'proporcao_venda' => 'nullable|string|max:15',
                'proporcao_consumo' => 'nullable|string|max:15',
                'id_exclusivo' => 'nullable|integer',
                'use_frete' => 'nullable|integer',
                'prazo' => 'nullable|integer',
                'show_in_cardpress' => 'nullable|integer',
                'src' => 'nullable|string',
                'qminima' => 'nullable|integer',
                'keywords' => 'nullable|string',
                'descricao' => 'nullable|string',
                'long_description' => 'nullable|string',
                'ficha_tecnica' => 'nullable|string',
                'itens_inclusos' => 'nullable|string',
                'especificacoes' => 'nullable|string',
                'opcoes' => 'nullable|string',
                'opcoes_3' => 'nullable|string',
                'opcoes_2' => 'nullable|string',
                'altura' => 'nullable|numeric|between:0,9999999999.99',
                'peso' => 'nullable|numeric|between:0,9999999999.999',
                'largura' => 'nullable|numeric|between:0,9999999999.99',
                'comprimento' => 'numeric|between:0,9999999999.99',
                'origem' => 'nullable|string',
                'sub_tributaria' => 'nullable|string',
                'origem_noie' => 'nullable|string',
                'aliquota' => 'nullable|numeric|between:0,9999999999.99',
                'publicar' => 'nullable|integer',
                'hits' => 'nullable|integer',
                'id_foto_default' => 'nullable|integer',
                'id_fabricante' => 'nullable|integer',
                'id_produto_avulso' => 'nullable|integer|unique:produtos',
                'demo' => 'integer',
                'gabarito' => 'nullable|string',

            ]);


            if ($validador->fails()) {
                return response()->json($validador->errors());
            }

            Produtos::create($request->all());

            return response()->json([
                "status" => "success",
                "message" => "produto cadastro com suscesso"
            ]);
        } catch (Exception $e) {
            return response()->json([
                "status" => "ERROR",
                "message" => "Desculpe estamos enfrentando problemas internos."
            ], 500);
        }
    }

    /**
     * Editar produto
     */
    public function editar(Request $request, $id_produto)
    {
        $validador = Validator::make($request->all(), [
            "sn" => "unique:produtos",
            'ativo' => 'integer',
            'nome' => 'string|unique:produtos',
        ]);

        if (count($request->input()) == 0) {
            return response()->json([
                "status" => "ERROR",
                "message" => "Para editar o produto passe ao menos algum campo valído não passe campos vazios."
            ], 400);
        }

        if ($validador->fails()) {
            return response()->json($validador->errors());
        }

        $produto = Produtos::where("id", $id_produto)->first();

        $produto->id_especializacao =  $request->id_especializacao ?? $produto->id_especializacao;
        $produto->ativo = $request->ativo ??  $produto->ativo;
        $produto->sn = $request->sn ?? $produto->sn;
        $produto->nome = $request->nome ?? $produto->nome;
        $produto->src_alt = $request->src_alt ?? $produto->src_alt;
        $produto->proporcao_venda = $request->proporcao_venda ?? $produto->proporcao_venda;
        $produto->proporcao_consumo = $request->proporcao_consumo ?? $produto->proporcao_consumo;
        $produto->id_exclusivo = $request->id_exclusivo ?? $produto->id_exclusivo;
        $produto->use_frete = $request->use_frete ?? $produto->use_frete;
        $produto->prazo = $request->prazo ?? $produto->prazo;
        $produto->show_in_cardpress = $request->show_in_cardpress ?? $produto->show_in_cardpress;
        $produto->src = $request->src ?? $produto->src;
        $produto->qminima = $request->qminima ?? $produto->qminima;
        $produto->keywords = $request->keywords ?? $produto->keywords;
        $produto->descricao = $request->descricao ?? $produto->descricao;
        $produto->long_description = $request->long_description ?? $produto->long_description;
        $produto->ficha_tecnica = $request->ficha_tecnica ?? $produto->ficha_tecnica;
        $produto->itens_inclusos = $request->itens_inclusos ?? $produto->itens_inclusos;
        $produto->opcoes = $request->opcoes  ?? $produto->opcoes;
        $produto->opcoes_2 = $request->opcoes_2  ?? $produto->opcoes_2;
        $produto->opcoes_3 = $request->opcoes_3  ?? $produto->opcoes_3;
        $produto->altura = $request->altura ?? $produto->altura;
        $produto->peso = $request->peso ?? $produto->peso;
        $produto->largura = $request->largura ?? $produto->largura;
        $produto->comprimento = $request->comprimento ?? $produto->comprimento;
        $produto->origem = $request->origem ?? $produto->origem;
        $produto->sub_tributaria = $request->sub_tributaria ?? $produto->sub_tributaria;
        $produto->origem_noie = $request->origem_noie ?? $produto->origem_noie;
        $produto->aliquota = $request->aliquota ?? $produto->aliquota;
        $produto->publicar = $request->piblicar ?? $produto->publicar;
        $produto->hits = $request->hits ?? $produto->hits;
        $produto->id_foto_default = $request->id_foto_default ?? $produto->id_foto_default;
        $produto->id_fabricante = $request->id_fabricante ?? $produto->id_fabricante;
        $produto->id_produto_avulso = $request->id_produto_avulso ?? $produto->id_produto_avulso;
        $produto->demo = $request->demo ?? $produto->demo;
        $produto->gabarito = $request->ganarito ?? $produto->gabarito;

        try {
            $produto->save();

            return response()->json([
                "status" => "SUCCESS",
                "message" => "Produto atualizado com suscesso."
            ]);
        } catch (Exception $e) {
            return response()->json([
                "status" => "ERROR",
                "message" => "Erro interno"
            ], 500);
        }
    }
}
