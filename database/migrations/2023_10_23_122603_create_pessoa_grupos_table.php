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
        Schema::create('pessoa_grupos', function (Blueprint $table) {
            $table->id();
            $table->longText('sessao');
            $table->longText('array_permissoes');
            $table->foreignId('id_grupo')->references('id')->on('grupos')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('id_pessoa')->references('id')->on('pessoas')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pessoa_grupos');
    }
};
