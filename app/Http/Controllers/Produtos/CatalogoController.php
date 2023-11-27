<?php

namespace App\Http\Controllers\Produtos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{ProdutoCatalogo, Categorias, Estoque};
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CatalogoController extends Controller
{
    public function precoMaxMin(Request $request)
    {
        $preco_max = Estoque::select("preco_vendido")->max();
        $preco_min = Estoque::select("preco_vendido")->min();

        return response()->json([
            "preco_min" => $preco_min,
            "preco_max" => $preco_max
        ]);
    }
    public function obterItensCarrinho(Request $request)
    {
        $itens_carrinho = explode(",", str_replace(["[", "]"], '', $request->itens_carrinho));

        $carrinho = ProdutoCatalogo::withWhereHas("produto", function (Builder $query) use ($itens_carrinho) {
            $query->whereIn("id", $itens_carrinho);
        })->with(["estoque", "categoria"])->get();

        return response()->json($carrinho);
    }

    public function obterPorCategoria(Request $request, $categoria)
    {

        $filter_parameters = explode(",", $request->query("categorias", ""));

        Log::info('Request received: ' . request()->url());
        Log::info("query: " . implode(explode(", ", $request->query("categorias", ""))));
        $preco_max = Estoque::select("preco_vendido")->max("preco_vendido");
        $preco_min = Estoque::select("preco_vendido")->min("preco_vendido");

        $categorias = Categorias::all("id");

        $catalogo = ProdutoCatalogo::withWhereHas("estoque")
            ->with([
                "produto",
                'subCategoria',
            ])
            ->withWhereHas("categoria", function(Builder $query) use ($categoria) {
                $query->where("categoria", $categoria);
            })
            ->whereIn("categoria_id", $request->query("categorias") ? $filter_parameters : $categorias)
            ->orWhereIn("sub_categoria_id", $request->query("categorias") ? $filter_parameters : $categorias)
            ->paginate($request->query("per_page") ? (int) $request->query("per_page") : 10);


        $data = array_merge([
            "dados" => $catalogo,
            "range" => [
                "max" => $preco_max,
                "min" => $preco_min,
                "d" => explode(",", $request->query("categorias"))
            ]
        ]);

        return response()->json($data);
    }
    public function obterCatalogoComEstoque(Request $request)
    {
        $filter_parameters = explode(",", $request->query("categorias", ""));

        Log::info('Request received: ' . request()->url());
        Log::info("query: " . implode(explode(", ", $request->query("categorias", ""))));
        $preco_max = Estoque::select("preco_vendido")->max("preco_vendido");
        $preco_min = Estoque::select("preco_vendido")->min("preco_vendido");

        $categorias = Categorias::all("id");
        $categorias_array = [];

        foreach($categorias as $c) {
            array_push($categorias_array, $c->id);
        }

    
        $catalogo = ProdutoCatalogo::withWhereHas("estoque")
            ->with([
                "produto",
                'subCategoria',
                "categoria"
            ])

            ->whereIn("categoria_id", $request->query("categorias") !== "undefined" || null || "" ? $filter_parameters : $categorias_array)
            ->orWhereIn("sub_categoria_id", $request->query("categorias") !== "undefined" || null || "" ? $filter_parameters : $categorias_array)
            ->paginate($request->query("per_page") ? (int) $request->query("per_page") : 10);


        $data = array_merge([
            "dados" => $catalogo,
            "range" => [
                "max" => $preco_max,
                "min" => $preco_min,
                "d" => explode(",", $request->query("categorias"))
            ]
        ]);

        return response()->json($data);
    }

    public function obterCatalogo(Request $request)
    {
        $catalogo = ProdutoCatalogo::with(["estoque", "categoria", "produto"])->paginate(10);

        return response()->json($catalogo);
    }

    public function obterProduto(Request $request, $url)
    {
        $catalogo = ProdutoCatalogo::withWhereHas(
            "produto",
            function (Builder $query) use ($url) {
                $query->where("url", $url);
            }
        )->with(["estoque", "categoria"])->first();

        if (!$catalogo || $catalogo == null) {
            return response()->json(["message" => "Produto não econtrado"], 404);
        }

        return response()->json($catalogo);
    }


}

