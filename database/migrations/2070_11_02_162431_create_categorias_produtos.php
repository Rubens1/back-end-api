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
        Schema::create('categorias_produtos', function (Blueprint $table) {
            $table->timestamps();
            $table->primary(['id_categoria', 'id_produto']);
            $table->foreignId('id_produto')
                ->references('id')->on('produtos')
                ->onDelete('cascade')
                ->onUpdate('cascade');
           $table->foreignId('id_categoria')
                ->references('id')->on('categorias')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categorias_produtos');
    }
};
