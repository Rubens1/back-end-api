<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Models\{
    Produtos,
    CategoriaProdutos,
    Estoque,
    Pessoas
};
use App\Helpers\{Logger, SlugHelper};

use Exception;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{
    Hash,
    Validator
};

class ProdutoController extends Controller
{
    use Logger, SlugHelper;
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public $pessoa;
    public $logger;

    public function __construct()
    {
        $this->middleware('auth:pessoas', ['except' => ['listarProdutos', "obterProduto", "listarEstoque"]]);

        $this->pessoa = auth('pessoas')->user();
    }

    /**
     * Lista todos os produtos de forma paginada
     */


    public function obterProduto(Request $request, $slug)
    {
        try {
            $produto = Estoque::withWhereHas(
                "produto",
                function (Builder $query) use ($slug) {
                    $query->where("src_alt", $slug);
                }
            )->first();

            if (!$produto || $produto == null) {
                return response()->json([
                    "not_found" => true,
                    "message" => "produto não econtrado"
                ], 400);
            }

            return response()->json($produto);

        } catch (Exception $e) {
            return response()->json([
                "error" => $e->getMessage(),
                "message" => "Erro interno"
            ], 500);
        }
    }

    public function listarProdutos()
    {
        $produtos = Produtos::paginate(10);

        return response()->json($produtos);
    }
    public function listarEstoque()
    {

        $produtos = Estoque::with("produto")->paginate(10);

        return response()->json($produtos);
    }

    /**
     * Registra um produto
     */
    public function cadastrarProduto(Request $request)
    {
        try {

            $validador = Validator::make($request->all(), [
                "sn" => "unique:produtos",
                'id_especializacao' => 'nullable|integer',
                'nome' => 'required|string|unique:produtos',
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
                'comprimento' => 'required|numeric|between:0,9999999999.99',
                'origem' => 'nullable|string',
                'sub_tributaria' => 'nullable|string',
                'origem_noie' => 'nullable|string',
                'aliquota' => 'nullable|numeric|between:0,9999999999.99',
                'publicar' => 'nullable|integer',
                'hits' => 'nullable|integer',
                'id_foto_default' => 'nullable|integer',
                'id_fabricante' => 'nullable|integer',
                'id_produto_avulso' => 'nullable|integer|unique:produtos',
                'gabarito' => 'nullable|string',

            ]);

            if ($validador->fails()) {
                return response()->json(["errors" => $validador->errors()], 422);
            }

            $slug = ["src_alt" => $this->generateSlug($request->nome)];

            $produto = Produtos::create(
                array_merge(
                    $request->all(),
                    $slug
                )
            )->fresh();

            $categoria = CategoriaProdutos::create([
                "id_produto" => $produto->id ?? null,
                "id_sub_categora" => $request->id_sub_categoria ?? null,
                "id_categoria" => $request->id_categoria ?? null
            ])->fresh();

            return response()->json([
                "status" => "success",
                "message" => "produto cadastro com suscesso"
            ]);


        } catch (Exception $e) {
            return response()->json([
                "error" => $e->getMessage(),
                "d" => $request->input(),
                "s" => $produto,
                "status" => "ERROR",
                "message" => "Desculpe estamos enfrentando problemas internos."
            ], 500);
        }
    }

    public function excluirProduto(Request $request, $id)
    {
        $estoque = Estoque::where("id_produto", $id)->delete();

        $produto = Produtos::where("id", $id)->delete();

        return response()->json([
            "status" => "success",
            "message" => "Excluído com suscesso"
        ]);
    }

    /**
     * 
     */

     public function obterProdutoRecomendado(Request $request)
     {
        try {
            $produto = Estoque::withWhereHas(
                "produto",
                function (Builder $query) use ($request) {
                    $query->where("id", $request->slug);
                }
            )->first();

            if (!$produto || $produto == null) {
                return response()->json([
                    "not_found" => true,
                    "message" => "produto não econtrado"
                ], 400);
            }

            return response()->json($produto);

        } catch (Exception $e) {
            return response()->json([
                "error" => $e->getMessage(),
                "message" => "Erro interno"
            ], 500);
        }
     }
    /**
     * Edita informações de um produto passando o id
     * passando ao menos algum campo para editar
     * caso passe nenhum vai ser retornado uma mensagem de erro.
     */
    public function editarProduto(Request $request, $id_produto)
    {
        try {

            $keys = [];

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

            foreach ($request->input() as $key => $value) {
                array_push($keys, $key);
            }

            foreach ($keys as $key) {
                $produto->update([
                    $key => $request->$key ?? $produto->$key
                ]);
            }

            $produto->save()->fresh();

            return response()->json([
                "message" => "Dados do produto atualizados",
                "fresh" => $produto
            ]);


        } catch (Exception $e) {
            return response()->json([
                "status" => "ERROR",
                "message" => "Erro interno"
            ], 500);
        }
    }
}