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
        Schema::create('pedidos_pagto', function (Blueprint $table) {
            $table->id();
            $table->string('vencimento', 255)->nullable();
            $table->string('valor', 255)->nullable();
            $table->enum('situacao', ['A VENCER', 'PAGO', 'CANCELADO'])->nullable();
            $table->tinyInteger('parcela')->default(1);
            $table->timestamps();
            $table->foreignId('id_pedido')->references('id')->on('pedidos')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedidos_pgmt');
    }
};
