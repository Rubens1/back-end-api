<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('op_frete', function (Blueprint $table) {
            $table->id();
            $table->string('frete', 255)->nullable();
            $table->text('funcao')->nullable();
            $table->string('descricao', 255)->nullable();
            $table->string('codigo', 255)->nullable();
            $table->integer('id_servico')->nullable();
            $table->string('cartao_postagem', 255)->nullable();
            $table->string('cod_contrato', 255)->nullable();
            $table->string('cod_administrativo', 255)->nullable();
            $table->integer('ativo')->default(1);
            $table->integer('pub_ativo')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('opfrete');
    }
};
