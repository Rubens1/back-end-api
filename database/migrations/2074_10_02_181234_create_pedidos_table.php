<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePedidosTable extends Migration
{
    public function up()
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('is_active')->default(1);
            $table->integer('id_pedido')->default(0);
            $table->integer('id_contrato')->default(0);
            $table->integer('id_bureau')->nullable();
            $table->string("codigo_frete")->unique();
            $table->string("codigo_pagamento")->unique();
            $table->enum('tipo', ['PEDIDO', 'ORCAMENTO', 'FATURA', 'AGRUPAMENTO', 'NF-ENTRADA', 'NF-DEVOLUCAO'])->default('PEDIDO');
            $table->decimal('custo_total', 10, 2)->default(0.00);
            $table->decimal('custo_frete', 10, 2)->default(0.00);
            $table->enum('sit_pagto', ['FATURADO', 'AG. PAGTO', 'PAGO', 'VENCIDO', 'CANCELADO', 'EM ATRASO'])->default('AG. PAGTO');
            $table->enum('sit_pedido', ['ABERTO', 'FECHANDO', 'EM COTACAO', 'FECHADO', 'APROVADO', 'CANCELADO', 'FINALIZADO', 'CONSOLIDADO', 'RECUSADO', 'REPROVADO', 'FATURADO'])->default('ABERTO');
            $table->enum('sit_entrega', ['AG. LIBERACAO', 'LIBERADO', 'REGISTRADO', 'EM TRANSITO', 'ENTREGUE', 'RETORNOU', 'NOVA TENTATIVA', 'DISP. RETIRADA', 'AGUARDANDO COLETA/TRANSPORTE'])->default('AG. LIBERACAO');
            $table->enum('sit_producao', ['AG. LIBERACAO', 'LIBERADO', 'IMPRIMINDO', 'EM PAUSA', 'FINALIZADO'])->default('AG. LIBERACAO');
            $table->date('vencimento')->nullable();
            $table->date('data_pagto')->nullable();
            $table->datetime('datahora')->nullable();
            $table->date('validade_proposta')->nullable();
            $table->string('prazo_producao', 15)->nullable();
            $table->tinyInteger('comissoes_pagas')->default(0);
            $table->text('obs')->nullable();
            $table->string('file_cotacao', 255)->nullable();
            $table->string('file_pedido', 255)->nullable();
            $table->tinyInteger('royalties_pagos')->default(0);
            $table->enum('priority', ['NORMAL', 'BAIXA', 'ALTA', 'URGENTE'])->default('NORMAL');
            $table->timestamps();
            $table->unsignedBigInteger('id_cliente');
            $table->unsignedBigInteger('id_op_frete')->nullable();
            $table->unsignedBigInteger('id_op_pagto')->nullable();
            $table->unsignedBigInteger('id_endereco');
            $table->unsignedBigInteger('id_pessoa');
            $table->unsignedBigInteger("id_artista")->nullable();
            $table->unsignedBigInteger('id_vendedor')->nullable();
            $table->string('numero_pedido', 100)->nullable();
            $table->string("forma_pgmt")->nullable();
            $table->foreign('id_cliente')->references('id')->on('pessoas')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('id_endereco')->references('id')->on('pessoas_enderecos');
            $table->foreign('id_op_frete')->nullable()->references('id')->on('op_frete');
            $table->foreign('id_op_pagto')->nullable()->references('id')->on('op_pagto');
            $table->foreign('id_artista')->references('id')->on('pessoas')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('id_pessoa')->references('id')->on('pessoas')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('id_vendedor')->references('id')->on('pessoas');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pedidos');
    }
}
    