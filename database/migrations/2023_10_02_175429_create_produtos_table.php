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
        Schema::create('produtos', function (Blueprint $table) {
            $table->id();
            $table->integer('id_especializacao')->nullable(); //
            $table->tinyInteger('ativo')->default(1); //
            $table->string('sn')->nullable(); //
            $table->string('codigo')->nullable(); //
            $table->integer('id_produto')->nullable(); //
            $table->string('nome')->nullable(); //
            $table->string('src_alt')->nullable(); //
            $table->string("url")->nullable();
            $table->string('cover')->nullable();
            $table->longText('src_imagens')->nullable();
            $table->string('proporcao_venda', 15)->default('1:1'); //
            $table->string('proporcao_consumo', 15)->default('1:1'); //
            $table->integer('id_exclusivo')->nullable(); //
            $table->tinyInteger('use_frete')->default(1); //
            $table->integer('prazo')->nullable(); //
            $table->tinyInteger('show_in_cardpress')->default(0); //
            $table->string('src')->nullable(); //
            $table->integer('qminima')->default(1); //
            $table->string('keywords')->nullable(); //
            $table->text('descricao')->nullable(); //
            $table->text('long_description')->nullable(); //
            $table->text('ficha_tecnica')->nullable(); //
            $table->text('itens_inclusos')->nullable(); //
            $table->text('especificacoes')->nullable(); //
            $table->string('opcoes')->nullable(); //
            $table->string('opcoes_3')->nullable(); //
            $table->string('opcoes_2')->nullable(); //
            $table->decimal('altura', 10, 2)->nullable(); //
            $table->decimal('peso', 10, 3)->unsigned()->nullable(); //
            $table->decimal('largura', 10, 2)->nullable(); //
            $table->decimal('comprimento', 10, 2)->nullable(); //
            $table->string('origem')->nullable(); //
            $table->string('sub_tributaria')->nullable(); //
            $table->string('origem_noie')->nullable(); //
            $table->decimal('aliquota', 10, 2)->nullable(); //
            $table->tinyInteger('publicar')->default(1); //
            $table->integer('hits')->nullable(); //
            $table->integer('id_foto_default')->nullable(); //
            $table->integer('id_fabricante')->nullable(); //
            $table->integer('id_produto_avulso')->nullable();
            $table->tinyInteger('demo')->default(0);
            $table->string('gabarito')->nullable();
            $table->timestamps();
            $table->unique('id');
            $table->unique('nome');
            $table->unique('sn');
            $table->softDeletes();
            //$table->foreignId("id_categoria")->references("id")->on("categorias");
            //$table->foreignId("id_estoque")->references("id")->on("estoque");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produtos');
        Schema::table("produtos", function(Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};

