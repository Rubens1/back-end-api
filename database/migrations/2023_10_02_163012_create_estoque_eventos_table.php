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
        Schema::create('estoque_eventos', function (Blueprint $table) {
            $table->id();
            $table->string('evento', 255)->nullable();
            $table->string('acao', 255)->nullable();
            $table->enum('tipo_evento', ['E', 'S', 'N', 'B', 'T', 'F', 'C'])->nullable();
            $table->string('who', 255)->nullable();
            $table->string('to_show', 255)->nullable();
            $table->string('requires', 255)->nullable();
            $table->tinyInteger('hide')->default(0);
            $table->string('descricao', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estoque_eventos');
    }
};
