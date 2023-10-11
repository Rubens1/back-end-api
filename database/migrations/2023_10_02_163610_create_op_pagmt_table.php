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
        Schema::create('op_pagto', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 255)->nullable();
            $table->string('titulo', 255)->nullable();
            $table->integer('ativo')->nullable();
            $table->string('codigo_nf', 255)->nullable();
            $table->text('descricao')->nullable();
            $table->integer('pub_ativo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('op_pagmt');
    }
};
