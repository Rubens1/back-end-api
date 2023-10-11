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
        Schema::create('estoque', function (Blueprint $table) {
            $table->id();
            $table->integer('id_origem')->nullable();
            $table->integer('id_destino')->nullable();
            $table->integer('id_pedido')->nullable();
            $table->integer('qtd')->nullable();
            $table->integer('id_pedido_item')->nullable();
            $table->integer('id_compra')->nullable();
            $table->string('opt', 18)->nullable();
            $table->datetime('datahora')->nullable();
            $table->string('obs', 255)->nullable();
            $table->enum('finalidade', ['USO_CONSUMO', 'VENDA', 'INDEFINIDO', 'CONSERTO', 'INVESTIMENTO'])->nullable();
            $table->string('grupo', 255)->nullable();
            $table->integer('id_agente')->nullable();
            $table->datetime('datahora_reg')->nullable();
            $table->integer('id_agente_remocao')->nullable();
            $table->integer('removido')->default(0);
            $table->datetime('datahora_remocao')->nullable();
            $table->string('nf', 255)->nullable();
            $table->integer('id_nf')->nullable();
            $table->decimal('preco_compra', 10, 2)->nullable();
            $table->decimal('preco_vendido', 10, 2)->nullable();
            $table->string('sn', 255)->nullable();
            $table->timestamps();
            $table->index('id_evento', 'IDX_estoque_id_evento');
            $table->index('id_pessoa', 'IDX_estoque_id_pessoa');
            $table->index('id_produto', 'IDX_estoque_id_produto');
            $table->index('opt', 'IDX_estoque_opt');
            $table->foreignId("id_categoria")
                  ->nullable()
                  ->references("id")
                  ->on("categorias");
            $table->foreignId('id_evento')
                ->nullable()
                ->references('id')->on('estoque_eventos')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreignId('id_pessoa')
                ->nullable()
                ->references('id')->on('pessoas');
            $table->foreignId('id_produto')
                ->unique()
                ->nullable()
                ->references('id')->on('produtos')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estoque');
    }
};
