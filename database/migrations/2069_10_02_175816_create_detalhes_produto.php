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
        Schema::create('detalhes_produtos', function (Blueprint $table) {
            $table->id();
            $table->decimal('preco', 10, 2)->nullable();
            $table->datetime('datahora')->nullable();
            $table->string('codigo', 30)->nullable();
            $table->string('grupo', 255)->nullable();
            $table->string('nome_nfs', 255)->nullable();
            $table->string('nome_danfe', 255)->nullable();
            $table->string('ncm', 255)->nullable();
            $table->string('prazo', 255)->nullable();
            $table->decimal('comissao', 10, 2)->nullable();
            $table->decimal('royaties', 10, 2)->nullable();
            $table->foreignId('id_produto')->references('id')->on('produtos')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps(); // Adds 'created_at' and 'updated_at' columns
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalhes_produto');
    }
};
