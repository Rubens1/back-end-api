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
        Schema::create('categorias', function (Blueprint $table) {
            $table->id();
            $table->string('categoria', 255)->nullable();
            $table->tinyInteger('show_cardpress')->default(0);
            $table->string('sn', 255)->nullable();
            $table->integer('id_categoria')->default(0);
            $table->string('descricao', 255)->nullable();
            $table->string('keywords', 255)->nullable();
            $table->string('capa', 255)->nullable();
            $table->string('ativo', 255)->default('1');
            $table->integer('hits')->default(0);
            $table->integer('ord')->default(0);
            $table->timestamps();
            $table->unique('id');
            $table->unique('sn');
            $table->unique(['id', 'categoria']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categorias');
    }
};
