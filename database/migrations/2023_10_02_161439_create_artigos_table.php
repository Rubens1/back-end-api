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
        Schema::create('artigos', function (Blueprint $table) {
            $table->id();
            $table->integer('id_pessoa')->nullable();
            $table->string('titulo', 255)->nullable();
            $table->string('img', 255)->nullable();
            $table->longText('texto')->nullable();
            $table->tinyInteger('ativo')->default(1);
            $table->datetime('datahora')->nullable();
            $table->string('intro', 255)->nullable();
            $table->string('keywords', 255)->nullable();
            $table->datetime('datahora_update')->nullable();
            $table->integer('hits')->default(0);
            $table->timestamps();

            $table->index('id_pessoa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('artigos');
    }
};
