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
        Schema::create('produto_catalogo', function (Blueprint $table) {
            $table->id();
            $table->foreignId("estoque_id")->nullable()->references("id")->on("estoque");
            $table->foreignId("categoria_id")->nullable()->references("id")->on("categorias");
            $table->foreignId("sub_categoria_id")->nullable()->references("id")->on("categorias");
            $table->foreignId("produto_id")->nullable()->references("id")->on("produtos");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produto_catalogo');
    }
};
