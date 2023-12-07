<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OpcoesPagamento;

class OperadorDePagamentoController extends Controller
{
    public function registrarNovoOperadorDePagamento(Request $request)
    {
        try {
            $opt_pgmt = OpcoesPagamento::create([
                "nome" => $request->nome,
                "titulo" => $request->titulo,
                "descricao" => $request->descricao,
                "pub_ativo" => $request->pub_ativo
            ])->fresh();

            return response()->json($opt_pgmt, 200);

        } catch (\Exception $e) {
            return response()->json([
                "message" => $e->getMessage()
            ]);
        }
    }
}
