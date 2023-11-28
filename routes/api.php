<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
//Controllers
use App\Http\Controllers\Pagamento\{
    PixController,
    CartaoController,
    CoraController
};

use App\Http\Controllers\Site\{
    BlogController,
    CarrinhoController,
    CategoriaController,
    ProdutoController,
    TagController
};

use App\Http\Controllers\Global\{
    ValidacaoController,
    PessoasController,
    SenhaController
};
use App\Http\Controllers\Frete\{
    MelhorEnvioController
};
use App\Http\Controllers\Admin\{
    ColaboradorController,
    GerenciamentoPedidoController,
    PermissaoController,
    NfeController,
    LoggerController,
    ColaboradorLoginController   

};

use App\Http\Controllers\Client\{
    BancoController,
    EnderecoController,
    PedidoController,
    ContatosController
};

use App\Http\Controllers\Produtos\{
    EstoqueController,
    CatalogoController
};

use Stevebauman\Location\Facades\Location;
use Tymon\JWTAuth\Facades\JWTAuth;

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


//Rotas públicas
Route::post('/pessoa-entrar', [PessoasController::class, 'entrar']);
Route::put('/pessoa-atualizar', [PessoasController::class, 'atualizarToken']);
Route::post('/pessoa-cadastro', [PessoasController::class, 'cadastro']);
Route::get("/list-all-roles", [PermissaoController::class, "listAllRoles"]);
Route::put("/atualizar-senha/{id}", [PessoasController::class, "atualizarSenha"]);

//Colaborador
Route::group(["prefix" => "colaboradores"], function() {
    Route::post("/cadastrar", [ColaboradorController::class, "cadastrarColaborador"]);
    Route::post("/login", [ColaboradorLoginController::class, "login"]);
});

//Senhas
Route::post("/recuperar-senha", [SenhaController::class, "enviarLinkDeRecupecaoDeSenha"]);
Route::post("/verificar-token-senha", [SenhaController::class, "verificarTokenDeRecuperacaoDeSenha"]);
Route::post("/alterar-senha", [SenhaController::class, "recuperarSenha"]);

//Produtos público
Route::get("/produtos", [ProdutoController::class, "listarProdutos"]);
Route::get("/estoque", [ProdutoController::class, "listarEstoque"]);
Route::get("/produto/{slug}", [ProdutoController::class, "obterProduto"]);

//Estoque   
Route::get("/estoque-lista", [EstoqueController::class, "obterEstoque"]);
Route::post("/registrar-produto-em-estoque", [EstoqueController::class, "registrarProdutoEmEstoque"]);
Route::put("/retirar-produto-do-estoque/{id_produto}", [EstoqueController::class, "retirarProdutoEstoque"]);
Route::put("/adicionar-no-estoque/{id_produto}", [EstoqueController::class, "adicionarAoEstoque"]);
Route::put("/editar-estoque/{id_produto}", [EstoqueController::class, "editarEstoque"]);


//Pessoas
Route::post('/pessoa-sair', [PessoasController::class, 'sair']);
Route::get('/pessoa-perfil', [PessoasController::class, 'pessoaPerfil']);
Route::get('/pessoas-lista-paginada', [PessoasController::class, 'listarPaginada']);
Route::get("/pessoas-lista", [PessoasController::class, "listar"]);
Route::get("/pessoa/{id}", [PessoasController::class, "info"]);
Route::put("/editar-pessoa/{id}", [PessoasController::class, "editarDadosCliente"]);


//Endereços
Route::get("/enderecos-cliente/{id}", [EnderecoController::class, "obterListaDeEnderecosDoCliente"]);
Route::get("/endereco/{id}", [EnderecoController::class, "obterEnderecoPeloId"]);
Route::post("/cadastrar-endereco-cliente", [EnderecoController::class, "cadastrarEndereco"]);
Route::put("/editar-endereco/{id}", [EnderecoController::class, "editarEndereco"]);
Route::put("/desativa-endereco/{id}", [EnderecoController::class, "desativaEndereco"]);
Route::put("/principal-endereco/{id}/{id_pessoa}", [EnderecoController::class, "principalEndereco"]);
Route::put("/excluir-endereco/{id}", [EnderecoController::class, "excluir"]);


//Categoria
Route::post("/cadastrar-categoria", [CategoriaController::class, "criarCategoria"]);
Route::delete("/excluir-categoria/{id}", [CategoriaController::class, "excluir"]);
Route::get("/categorias/{id}", [CategoriaController::class, "info"]);
Route::get("/categorias", [CategoriaController::class, "listar"]);
Route::put("/editar-categoria/{id}", [CategoriaController::class, "editar"]);
Route::get("/categoria-produto/{produto_id}", [CategoriaController::class, "obterCategoriaPorProduto"]);
Route::get("/subcategorias/{id}", [CategoriaController::class, "subcategorias"]);

//Nota fiscal
Route::get('/nfe', [NfeController::class, 'listar']);
Route::post('/registrar-nfe', [NfeController::class, 'registrar']);
Route::get('/nfe/{id}', [NfeController::class, 'info']);


//Retorna todos os pedidos de forma resumida e paginada.
Route::get("/lista/pedidos/", [PedidoController::class, "listar"]);
Route::get("/lista/pedidos/cliente/{cliente_id}", [PedidoController::class, "listaPorClienteId"]);
Route::get("/lista/vendas/{vendedor_id}", [PedidoController::class, "listarPorVendedorId"]);
Route::get("/lista/pedidos-por-status", [PedidoController::class, "listarPorStatus"]);

//Criação de pedidos
Route::post("/criar-pedido", [PedidoController::class, "criarPedido"]);

//Informações detalhadas de pedido e vendas
Route::get("/pedido/{pedido_id}", [PedidoController::class, "obtePorPedidoId"]);
Route::get("/venda/vendedor/{vendedor_id}", [PedidoController::class, "obterPorVendedorId"]);
Route::get("/pedido/cliente/{id}", [PedidoController::class, "obterPorClienteId"]);

//Alteraçao de status de itens do pedido
Route::put("/mudar-status-pedido/{pedido_id}", [GerenciamentoPedidoController::class, "atualizarStatusPedido"]);
Route::put("/mudar-status-entrega/{pedido_id}", [GerenciamentoPedidoController::class, "atualizarStatusEntrega"]);
Route::put("/mudar-status-pagamento/{pedido_id}", [GerenciamentoPedidoController::class, "atualizarStatusPagamento"]);
Route::put("/mudar-status-producao/{pedido_id}", [GerenciamentoPedidoController::class, "atualizarStatusProducao"]);

//Produtos  
Route::post("/cadastrar-produto", [ProdutoController::class, "cadastrarProduto"]);
Route::put("/editar-produto/{produto_id}", [ProdutoController::class, "editarProduto"]);
Route::put("/deletar-produto/{id}", [ProdutoController::class, "excluirProduto"]);

//Estoque   
Route::get("/estoque-lista", [EstoqueController::class, "obterEstoque"]);
Route::post("/registrar-produto-em-estoque", [EstoqueController::class, "registrarProdutoEmEstoque"]);
Route::put("/retirar-produto-do-estoque/{id_produto}", [EstoqueController::class, "retirarProdutoEstoque"]);
Route::put("/adicionar-no-estoque/{id_produto}", [EstoqueController::class, "adicionarAoEstoque"]);
Route::put("/editar-estoque/{id_produto}", [EstoqueController::class, "editarEstoque"]);

//Logs
Route::get("/logs/{id}", [LoggerController::class, "obterLogs"]);
Route::get("/logs-lista", [LoggerController::class, "lista"]);
Route::post("/criar-log", [LoggerController::class, "criarLog"]);

//Validações
Route::get("/cpf/{cpf}", [ValidacaoController::class, "validaCPF"]);
Route::get("/cnpj/{cnpj}", [ValidacaoController::class, "validaCNPJ"]);
Route::get("/cep/{cep}", [ValidacaoController::class, "buscaCEP"]);

//Admin Routes
Route::group([], function () {
    Route::put("/grant-to/{id_pessoa}", [PermissaoController::class, "grantTo"]);
    Route::put("/revoke-to/{id_pessoa}", [PermissaoController::class, "revokeTo"]);
    Route::post("/create-role", [PermissaoController::class, "createRole"]);
    Route::post("/edit-role", [PermissaoController::class, "editRole"]);
    
});

//Contatos
Route::get("/contatos/{id_pessoa}", [ContatosController::class, "lista"]);
Route::post('/cadastrar-contato', [ContatosController::class, 'cadastrar']);
Route::put("/editar-contato/{id}", [ContatosController::class, "editar"]);
Route::delete("/deletar-contato/{id}", [ContatosController::class, "excluir"]);

//Banco
Route::get("/banco/{id_pessoa}", [BancoController::class, "lista"]);
Route::post('/cadastrar-banco', [BancoController::class, 'cadastrar']);
Route::put("/editar-banco/{id}", [BancoController::class, "editar"]);
Route::delete("/deletar-banco/{id}", [BancoController::class, "excluir"]);

//Pagamento
Route::post("/pix", [PixController::class, "generateQrCode"]);
Route::post("/mercado-pago", [CartaoController::class, "mercadoPago"]);
Route::post("/token-cora", [CoraController::class, "token"]);
Route::post("/pix-cora", [CoraController::class, "pix"]);
Route::post("/boleto-cora", [CoraController::class, "boleto"]);
Route::get("/lista-cora", [CoraController::class, "lista"]);

//Projetos
Route::group([], function() {
    Route::post("/criar-projeto", [ProjetoController::class, "criarProjeto"]);
    Route::get("/ler-projetos/{id}", [ProjetoController:: class, "lerProjetos"]);
    Route::post("/salvar-projeto", [ProjetoController::class, "salvarProjeto"]);
    Route::delete("/excluir-projeto", [ProjetoController::class, "excluir-projeto"]);
});

//Catalogo
Route::group([], function() { 
    Route::get("/catalogo", [CatalogoController::class, "obterCatalogo"]);
    Route::get("/catalogo/{id}", [CatalogoController::class, "obterProduto"]);
    Route::get("/catalogo-estoque", [CatalogoController::class, "obterCatalogoComEstoque"]);
    Route::post("/itens-carrinho", [CatalogoController::class, "obterItensCarrinho"]);
    Route::get("/catalogo-estoque/{categoria}", [CatalogoController::class, "obterPorCategoria"]);
    //Route::get("/price-range", CatalogoController::class, "precoMaxMin"]);
});

//Blog
Route::delete("/excluir-post", [BlogController::class, "excluirPost"]);
Route::get("/lista-post", [BlogController::class, "lista"]);
Route::post("/cadastra-post", [BlogController::class, "cadastraPost"]);
Route::get("/post/{id}", [BlogController::class, "post"]);
Route::put("/editar-post/{id}", [BlogController::class, "editarPost"]);
Route::delete("/excluir-tag", [TagController::class, "excluir"]);
Route::get("/lista-tag", [TagController::class, "lista"]);
Route::post("/cadastra-tag", [TagController::class, "cadastraTag"]);
Route::get("/tag/{id}", [TagController::class, "tag"]);
Route::put("/editar-tag/{id}", [TagController::class, "editarTag"]);

//Frete
Route::post("/melhor-envio", [MelhorEnvioController::class, "cotacao"]);
Route::post("/carrinho-envio", [MelhorEnvioController::class, "carrinho"]);