<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePedidosItensTable extends Migration
{
    public function up()
    {
        Schema::create('pedidos_itens', function (Blueprint $table) {
            $table->id();
            $table->enum('situacao', ['PENDENTE', 'EM REVISAO', 'REVISADO', 'NA FILA', 'IMPRESSO', 'CANCELADO', 'ERROR', 'LIBERADO'])->nullable();
            $table->text('item_data')->nullable();
            $table->integer('qtd')->nullable();
            $table->decimal('total', 10, 2)->nullable();
            $table->decimal('preco', 10, 2)->nullable();
            $table->decimal('valor_royalties', 10, 2)->default(0.00);
            $table->decimal('perc_royalties', 10, 2)->nullable();
            $table->integer('royalties_pagos')->default(0);
            $table->decimal('perc_comissao', 10, 2)->nullable();
            $table->string('tipo_cartao', 20)->nullable();
            $table->integer('coresFrente')->nullable();
            $table->integer('coresVerso')->nullable();
            $table->decimal('custoProducao', 10, 2)->nullable();
            $table->decimal('repasseServico', 10, 2)->nullable();
            $table->decimal('repasseManutencao', 10, 2)->nullable();
            $table->decimal('custoColor', 10, 2)->nullable();
            $table->integer('id_produto_estoque')->nullable();
            $table->decimal('preco_compra', 10, 2)->nullable();
            $table->decimal('valor_comissao', 10, 2)->nullable();
            $table->integer('creditado')->default(0);
            $table->integer('bx_estoque')->default(0);
            $table->timestamps();
            $table->unsignedBigInteger('id_produto');
            $table->unsignedBigInteger('id_pedido');
            $table->string('numero_pedido', 100)->nullable();
            $table->foreign('id_produto')->references('id')->on('produtos')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('id_pedido')->references('id')->on('pedidos')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pedidos_itens');
    }
}
