<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
//Controllers
use App\Http\Controllers\Pagamento\{
    MPController,
    CoraController
};

use App\Http\Controllers\Site\{
    BlogController,
    CarrinhoController,
    CategoriaController,
    TagController,
    ProdutoController
};

use App\Http\Controllers\Frete\{
    MelhorEnvioController
};

use App\Http\Controllers\Global\{
    ValidacaoController,
    PessoasController,
    SenhaController,
    ProjetoController
};

use App\Http\Controllers\Admin\{
    ColaboradorController,
    GerenciamentoPedidoController,
    PermissaoController,
    NfeController,
    LoggerController,
    ColaboradorLoginController,
    OperadorDePagamentoController,
    GrupoController,
    PessoaGrupoController

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
Route::post("/envio-cadastro", [PessoasController::class, "preCadastro"]);
Route::post("/contato-cadastro", [PessoasController::class, "preCadastroContato"]);
Route::get("/lista-criadores", [PessoasController::class, "listaCriadores"]);
Route::get("/perfil-criador/{id}", [PessoasController::class, "perfilCriador"]);
Route::post("/busca-criador", [PessoasController::class, "buscaCriador"]);

/**
 * Colaborador
 */
Route::group(["prefix" => "colaboradores"], function () {
    Route::post("/cadastrar", [ColaboradorController::class, "cadastrarColaborador"]);
    Route::post("/login", [ColaboradorLoginController::class, "login"]);
});

/**
 * Senhas
 */
Route::post("/recuperar-senha", [SenhaController::class, "enviarLinkDeRecupecaoDeSenha"]);
Route::post("/verificar-token-senha", [SenhaController::class, "verificarTokenDeRecuperacaoDeSenha"]);
Route::post("/alterar-senha", [SenhaController::class, "recuperarSenha"]);

/**
 * Produtos público
 */
Route::get("/produtos", [ProdutoController::class, "listarProdutos"]);
Route::get("/estoque", [ProdutoController::class, "listarEstoque"]);
Route::get("/produto/{slug}", [ProdutoController::class, "obterProduto"]);

/**
 * Pessoas
 */
Route::post('/pessoa-sair', [PessoasController::class, 'sair']);
Route::get('/pessoa-perfil', [PessoasController::class, 'pessoaPerfil']);
Route::get('/pessoas-lista-paginada', [PessoasController::class, 'listarPaginada']);
Route::get("/pessoas-lista", [PessoasController::class, "listar"]);
Route::get("/pessoa/{id}", [PessoasController::class, "info"]);
Route::put("/editar-pessoa/{id}", [PessoasController::class, "editarDadosCliente"]);

/**
 * Endereços
 */
Route::get("/enderecos-cliente/{id}", [EnderecoController::class, "obterListaDeEnderecosDoCliente"]);
Route::get("/endereco/{id}", [EnderecoController::class, "obterEnderecoPeloId"]);
Route::post("/cadastrar-endereco-cliente", [EnderecoController::class, "cadastrarEndereco"]);
Route::put("/editar-endereco/{id}", [EnderecoController::class, "editarEndereco"]);
Route::put("/desativa-endereco/{id}", [EnderecoController::class, "desativaEndereco"]);
Route::put("/principal-endereco/{id}/{id_pessoa}", [EnderecoController::class, "principalEndereco"]);
Route::delete("/excluir-endereco/{id}", [EnderecoController::class, "excluir"]);

/**
 * Categoria Controller
 */
Route::post("/cadastrar-categoria", [CategoriaController::class, "criarCategoria"]);
Route::delete("/excluir-categoria/{id}", [CategoriaController::class, "excluir"]);
Route::get("/categorias/{id}", [CategoriaController::class, "info"]);
Route::get("/categorias", [CategoriaController::class, "listar"]);
Route::put("/editar-categoria/{id}", [CategoriaController::class, "editar"]);
Route::get("/listar-categorias", [CategoriaController::class, "listarCategorias"]);
Route::get("/categoria-produto/{produto_id}", [CategoriaController::class, "obterCategoriaPorProduto"]);
Route::get("/subcategorias/{id}", [CategoriaController::class, "subcategorias"]);


//Nota fiscal
Route::get('/nfe', [NfeController::class, 'listar']);
Route::post('/registrar-nfe', [NfeController::class, 'registrar']);
Route::get('/nfe/{id}', [NfeController::class, 'info']);

/***
 * Retorna todos os pedidos de forma resumida e paginada.
 */
Route::get("/lista/pedidos/", [PedidoController::class, "listar"]);
Route::get("/lista/pedidos/cliente/{cliente_id}", [PedidoController::class, "listaPorClienteId"]);
Route::get("/lista/vendas/{vendedor_id}", [PedidoController::class, "listarPorVendedorId"]);
Route::get("/lista/pedidos-por-status", [PedidoController::class, "listarPorStatus"]);

/**
 * 
 * Criação de pedidos
 */
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
Route::delete("/deletar-produto/{id}", [ProdutoController::class, "excluirProduto"]);

//Estoque   
Route::get("/estoque-lista", [EstoqueController::class, "obterEstoque"]);
Route::post("/registrar-produto-em-estoque", [EstoqueController::class, "registrarProdutoEmEstoque"]);
Route::put("/retirar-produto-do-estoque/{id_produto}", [EstoqueController::class, "retirarProdutoEstoque"]);
Route::put("/adicionar-no-estoque/{id_produto}", [EstoqueController::class, "adicionarAoEstoque"]);
Route::put("/editar-estoque/{id_produto}", [EstoqueController::class, "editarEstoque"]);
/**
 * Logs
 */
Route::get("/logs/{id}", [LoggerController::class, "obterLogs"]);
Route::get("/logs-lista", [LoggerController::class, "lista"]);
Route::post("/criar-log", [LoggerController::class, "criarLog"]);
/**
 * Validações
 */
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

//Blog
Route::delete("/excluir-post/{id}", [BlogController::class, "excluirPost"]);
Route::get("/lista-posts", [BlogController::class, "listar"]);
Route::post("/cadastra-post", [BlogController::class, "cadastraPost"]);
Route::get("/post/{url}", [BlogController::class, "post"]);
Route::put("/editar-post/{id}", [BlogController::class, "editarPost"]);
Route::delete("/excluir-tag/{id}", [TagController::class, "excluir"]);
Route::get("/lista-tags", [TagController::class, "listar"]);
Route::post("/cadastra-tag", [TagController::class, "cadastraTag"]);
Route::get("/tag/{id}", [TagController::class, "tag"]);
Route::put("/editar-tag/{id}", [TagController::class, "editarTag"]);
Route::get("/tag-post/{id_post}", [BlogController::class, "tagsPost"]);
Route::post("/cadastra-post-tags", [BlogController::class, "postTag"]);
Route::get("/lista-tag-post/{id_tag}", [BlogController::class, "listaPostTag"]);
Route::get("/tag-link/{url}", [TagController::class, "tagLink"]);
Route::post("/busca-post", [BlogController::class, "pesquisaPost"]);
Route::post("/busca-post-tag", [BlogController::class, "pesquisaPostTag"]);

//Contatos
Route::get("/contatos/{id_pessoa}", [ContatosController::class, "listar"]);
Route::post('/cadastrar-contato', [ContatosController::class, 'cadastrar']);
Route::put("/editar-contato/{id}", [ContatosController::class, "editar"]);
Route::delete("/deletar-contato/{id}", [ContatosController::class, "excluir"]);

//Banco
Route::get("/banco/{id_pessoa}", [BancoController::class, "listar"]);
Route::post('/cadastrar-banco', [BancoController::class, 'cadastrar']);
Route::put("/editar-banco/{id}", [BancoController::class, "editar"]);
Route::delete("/deletar-banco/{id}", [BancoController::class, "excluir"]);

//Projetos
Route::group([], function () {
    Route::post("/criar-projeto", [ProjetoController::class, "criarProjeto"]);
    Route::get("/ler-projetos/{id}", [ProjetoController::class, "lerProjetos"]);
    Route::post("/salvar-projeto", [ProjetoController::class, "salvarProjeto"]);
    Route::delete("/excluir-projeto", [ProjetoController::class, "excluir-projeto"]);
});

//Catalogo
Route::group([], function () {
    Route::get("/catalogo", [CatalogoController::class, "obterCatalogo"]);
    Route::get("/catalogo/{id}", [CatalogoController::class, "obterProduto"]);
    Route::get("/catalogo-estoque", [CatalogoController::class, "obterCatalogoComEstoque"]);
    Route::post("/itens-carrinho", [CatalogoController::class, "obterItensCarrinho"]);
    Route::get("/catalogo-estoque/{categoria}", [CatalogoController::class, "obterPorCategoria"]);
    //Route::get("/price-range", CatalogoController::class, "precoMaxMin"]);
});

//Gera tokem cora
Route::post("/token-cora", [CoraController::class, "token"]);

//Grupo de rotas que precisam do token do cora
Route::group(["middleware" => "coraToken"], function () {
    Route::post("/boleto", [CoraController::class, "boleto"]);
    Route::post("/pix", [CoraController::class, "pix"]);
    Route::get("/pagamentos-cora", [CoraController::class, "pagamentos"]);
    Route::delete("/cancelar-boleto/{codigo_pagamento}", [CoraController::class, "cancelarBoleto"]);
});

//Mercado Pago
Route::group(["prefix" => "mp/"], function() {
    Route::post("/proccess-payment", [MPController::class, "proccessPayment"]);
});

Route::group([], function() {
    Route::post("/criar-pedido", [PedidoController::class, "criarPedido"]);
    Route::post("/listar-pedidos-cliente/{cliente_id}", [PedidoController::class, "listarPedidosClientePorId"]);
});


//Operadores
Route::post("/registrar-op-pagamento", [OperadorDePagamentoController::class, "registrarNovoOperadorDePagamento"]);

//Frete
Route::group(["prefix" => "frete"], function() {
    Route::post("/melhor-envio", [MelhorEnvioController::class, "cotacao"]);
    Route::post("/carrinho-envio", [MelhorEnvioController::class, "carrinho"]);
    Route::post("/etiqueta-frete", [MelhorEnvioController::class, "gerarEtiqueta"]);
    Route::get("/lista-carrinho-envio", [MelhorEnvioController::class, "listaCarrinho"]);
    Route::post("/pagar-frete", [MelhorEnvioController::class, "pagarFrete"]);
    Route::get("/lista-etiqueta", [MelhorEnvioController::class, "listaEtiqueta"]);
    Route::get("/info-etiqueta/{id}", [MelhorEnvioController::class, "infoEtiqueta"]);
    Route::get("/pesquisa-etiqueta", [MelhorEnvioController::class, "pesquisaEtiqueta"]);
    Route::post("/verifica-etiqueta", [MelhorEnvioController::class, "verificaEtiqueta"]);
    Route::post("/cancela-etiqueta", [MelhorEnvioController::class, "cancelaEtiqueta"]);
    Route::post("/rastreio", [MelhorEnvioController::class, "rastreio"]);
    Route::get("/saldo-frete", [MelhorEnvioController::class, "saldoMelhorEnvio"]);
    Route::post("/adicionar-saldo-frete", [MelhorEnvioController::class, "adicionaSaldo"]);
    Route::post("/imprimir-etiqueta", [MelhorEnvioController::class, "imprimirEtiqueta"]);
    Route::post("/cadastra-loja", [MelhorEnvioController::class, "cadastraLoja"]);
    Route::get("/lista-lojas", [MelhorEnvioController::class, "listaLojas"]);
    Route::get("/info-loja/{id}", [MelhorEnvioController::class, "verLoja"]);
    Route::post("/cadastra-endereco/{id}", [MelhorEnvioController::class, "cadastraEnderecoLoja"]);
    Route::get("/lista-enderecos/{id}", [MelhorEnvioController::class, "listaEnderecosLojas"]);
    Route::get("/lista-telefones/{id}", [MelhorEnvioController::class, "listaTelefonesLojas"]);
    Route::post("/cadastra-telefone/{id}", [MelhorEnvioController::class, "cadastraTelefone"]);
});

//Grupos
Route::delete("/deletar-grupo/{id}", [GrupoController::class, "excluir"]);
Route::put("/editar-grupo/{id}", [GrupoController::class, "editar"]);
Route::post("/cadastrar-grupo", [GrupoController::class, "cadastrar"]);
Route::get("/lista-grupos", [GrupoController::class, "listar"]);

//Pessoa Grupo
Route::group(["prefix" => "pessoas-grupos"], function() {
    Route::delete("/deletar-grupo/{id}", [PessoaGrupoController::class, "excluir"]);
    Route::put("/editar-grupo/{id}", [PessoaGrupoController::class, "editar"]);
    Route::post("/cadastrar-grupo", [PessoaGrupoController::class, "cadastrar"]);
    Route::get("/lista-grupos", [PessoaGrupoController::class, "listar"]);
});