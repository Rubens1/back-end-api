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
        Schema::create('produtos_lojas', function (Blueprint $table) {
            $table->id();
            $table->integer('id_loja')->nullable();
            $table->string('datahora', 255)->nullable();
            $table->foreignId('id_produto')->references('id')->on('produtos')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produtos_loja');
    }
};
