<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PessoasController;

use App\Http\Controllers\Api\Admin\{
    ProdutoController,
    PedidoController,
    AdminLoginController,
    GerenciamentoPedidoController,
    NFEController,
    CategoriaController,
    EnderecoController,
    EstoqueController
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

//Pessoa
Route::post('/pessoa-entrar', [PessoasController::class, 'entrar']);
Route::post('/pessoa-sair', [PessoasController::class, 'sair']);
Route::post('/pessoa-cadastro', [PessoasController::class, 'cadastro']);
Route::put('/pessoa-atualizar', [PessoasController::class, 'atualizarToken']);
Route::get('/pessoa-perfil', [PessoasController::class, 'pessoaPerfil']);
Route::get('/pessoa-lista', [PessoasController::class, 'listar']);
Route::get('/pessoas-lista-paginada', [PessoasController::class, 'listarPaginada']);
Route::get("/pessoa/{id}", [PessoasController::class, "info"]);
Route::post("/cadastrar-endereco-cliente", [PessoasController::class, "cadastrarEndereco"]);
Route::put("/editar-cliente/{id}", [PessoasController::class, "editarDadosCliente"]);

//Endereço
Route::get("/enderecos-cliente/{id}", [EnderecoController::class, "obterListaDeEnderecosDoCliente"]);
Route::get("/endereco/{id}", [EnderecoController::class, "obterEnderecoPeloId"]);
Route::post("/cadastrar-endereco-cliente", [EnderecoController::class, "cadastrarEndereco"]);
Route::put("/editar-endereco/{id}", [EnderecoController::class, "editarEndereco"]);

//Categoria
Route::get("/categorias", [CategoriaController::class, "listar"]);
Route::post("/cadastrar-categoria", [CategoriaController::class, "criarCategoria"]);
Route::delete("/excluir-categoria/{id}", [CategoriaController::class, "excluir"]);
Route::get("/categorias/{id}", [CategoriaController::class, "info"]);
Route::put("/editar-categoria/{id}", [CategoriaController::class, "editar"]);


//Nota fiscal
Route::get('/nfe', [NfeController::class, 'listar']);
Route::post('/registrar-nfe', [NfeController::class, 'registrar']);
Route::get('/nfe/{id}', [NfeController::class, 'info']);



//Pedido
Route::get("/lista/pedidos/", [PedidoController::class, "listar"]);
Route::get("/lista/pedidos/cliente/{cliente_id}", [PedidoController::class, "listaPorClienteId"]);
Route::get("/lista/vendas/{vendedor_id}", [PedidoController::class, "listarPorVendedorId"]);
Route::get("/lista/pedidos-por-status", [PedidoController::class, "listarPorStatus"]);
Route::post("/criar-pedido", [PedidoController::class, "criarPedido"]);
Route::get("/pedido/{pedido_id}", [PedidoController::class, "obtePorPedidoId"]);
Route::get("/venda/vendedor/{vendedor_id}", [PedidoController::class, "obterPorVendedorId"]);
Route::get("/pedido/cliente/{id}", [PedidoController::class, "obterPorClienteId"]);

//Gerenciamento de Pedido
Route::put("/mudar-status-pedido/{pedido_id}", [GerenciamentoPedidoController::class, "atualizarStatusPedido"]);
Route::put("/mudar-status-entrega/{pedido_id}", [GerenciamentoPedidoController::class, "atualizarStatusEntrega"]);
Route::put("/mudar-status-pagamento/{pedido_id}", [GerenciamentoPedidoController::class, "atualizarStatusPagamento"]);
Route::put("/mudar-status-producao/{pedido_id}", [GerenciamentoPedidoController::class, "atualizarStatusProducao"]);

//Produtos
Route::get("/produtos", [ProdutoController::class, "listar"]);
Route::post("/cadastrar-produto", [ProdutoController::class, "cadastrarProduto"]);
Route::put("/editar-produto/{produto_id}", [ProdutoController::class, "editarProduto"]);
Route::delete("/deletar-produto/{id}", [ProdutoController::class, "excluirProduto"]);

//Estoque
Route::post("/registrar-produto-em-estoque", [EstoqueController::class, "registrarProdutoEmEstoque"]);
Route::put("/retirar-produto-do-estoque/{id_produto}", [EstoqueController::class, "retirarProdutoEstoque"]);
Route::put("/adicionar-no-estoque/{id_produto}", [EstoqueController::class, "adicionarAoEstoque"]);
Route::put("/editar-estoque/{id_produto}", [EstoqueController::class, "editarEstoque"]);

