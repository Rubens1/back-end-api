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
        Schema::create('pessoas_enderecos', function (Blueprint $table) {
            $table->id();
            $table->enum('situacao', ['Ativo', 'Inativo'])->default('Ativo');
            $table->string('filial', 60)->nullable();
            $table->string("identificacao")->nullable();
            $table->boolean("principal")->nullable()->default(false);
            $table->string('logradouro', 100)->nullable();
            $table->string('numero', 20)->nullable();
            $table->string('complemento', 45)->nullable();
            $table->string('bairro', 50)->nullable();
            $table->string('cep', 10)->nullable();
            $table->string('cidade', 50)->nullable();
            $table->string('cod_municipio', 10)->nullable();
            $table->char('estado', 2);
            $table->string('responsavel', 60);
            $table->string('rg_responsavel', 10)->nullable();
            $table->string('ip_cadastro', 15)->default('');
            $table->text('obs')->nullable();
            $table->integer('active')->default(1);
            $table->tinyInteger('favorite')->unsigned()->default(0);
            $table->integer('id_usuario')->nullable();
            $table->integer('cod_uf')->nullable();
            $table->string('fone', 254)->nullable();
            $table->string('retirada', 8)->default('0');
            $table->string('impressao', 8)->default('0');
            $table->string('material', 8)->default('0');
            $table->string('venda', 8)->default('0');
            $table->string('layout', 8)->default('0');
            $table->string('faz_entrega', 8)->default('0');
            $table->decimal('tx_entrega', 10, 2)->nullable();
            $table->integer('km_lim_entrega')->default(0);
            $table->string('foto', 255)->nullable();
            $table->string('recebe_dinheiro', 8)->nullable()->default('0');
            $table->integer('km_lim_entrega_ex')->nullable();
            $table->decimal('tx_entrega_ex', 10, 2)->nullable();
            $table->string('referencia', 255)->nullable();
            $table->integer('id_matriz')->nullable();
            $table->string('faz_entrega_ex', 8)->default('0');
            $table->timestamps();
            $table->foreignId('id_pessoa')->references('id')->on('pessoas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pessoas_enderecos');
    }
};
