<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    PessoaController,
    NfeController,
    CategoriaController,
    PedidoController,
    ProdutoController
};


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//API Pessoas
Route::post('/pessoa-entrar', [PessoaController::class, 'entrar']);
Route::post('/pessoa-sair', [PessoaController::class, 'sair']);
Route::post('/pessoa-cadastro', [PessoaController::class, 'cadastro']);
Route::get('/pessoa-perfil', [PessoaController::class, 'pessoaPerfil']);
Route::get('/pessoa-lista', [PessoaController::class, 'lista']); 

//API Nfe
Route::get('/lista-nfe', [NfeController::class, 'lista']);
Route::post('/registrar-nfe', [NfeController::class, 'registrar']);
Route::get('/nfe/{id}', [NfeController::class, 'info']);

//API Categoria
Route::get("/categorias", [CategoriaController::class, "lista"]);
Route::post("/cadastrar-categoria", [CategoriaController::class, "cadastrar"]);
Route::get("/categorias/{id}", [CategoriaController::class, "info"]);

//API Pedidos
Route::get("/pedidos", [PedidoController::class, "lista"]);
Route::get("/pedidos/cliente/{cliente_id}", [PedidoController::class, "clienteId"]);
Route::get("/vendas/vendedor/{vendedor_id}", [PedidoController::class, "vendedorId"]);
Route::get("/pedidos-por-status", [PedidoController::class, "orderStatus"]);
Route::post('/registrar-pedido', [PedidoController::class, 'registrar']);

//API Produtos
Route::get("/produtos", [ProdutoController::class, "lista"]);
Route::post("/cadastrar-produto", [ProdutoController::class, "cadastra"]);
Route::patch("/editar-produto/{produto_id}", [ProdutoController::class, "editar"]);


//API Estoque
Route::post("/registrar-produto-em-estoque", [EstoqueController::class, "registraNoEstoque"]);
Route::post("/retirar-produto-do-estoque/{id_produto}", [EstoqueController::class, "adicionarNoEstoque"]);