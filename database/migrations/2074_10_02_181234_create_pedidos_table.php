<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->boolean("is_active")->default(true);
            $table->integer('id_pedido')->default(0);
            $table->integer('id_contrato')->default(0);
            $table->integer('id_bureau')->nullable();
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
            $table->boolean('comissoes_pagas')->default(false);
            $table->text('obs')->nullable();
            $table->string('file_cotacao', 255)->nullable();
            $table->string('file_pedido', 255)->nullable();
            $table->boolean('royalties_pagos')->default(false);
            $table->enum('priority', ['NORMAL', 'BAIXA', 'ALTA', 'URGENTE'])->default('NORMAL');
            $table->timestamps();
            $table->foreignId('id_cliente')->references('id')->on('pessoas')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('id_op_frete')->references('id')->on('op_frete');
            $table->foreignId('id_op_pagto')->references('id')->on('op_pagto');
            $table->foreignId('id_endereco')->references('id')->on('pessoas_enderecos');
            $table->foreignId('id_pessoa')->references('id')->on('pessoas')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('id_vendedor')->references('id')->on('pessoas');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};
