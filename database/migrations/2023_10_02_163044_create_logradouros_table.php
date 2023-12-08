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
        Schema::create('logradouros', function (Blueprint $table) {
            $table->integer('id')->unsigned();
            $table->integer('id_bairro')->unsigned()->nullable();
            $table->integer('tipo')->unsigned()->nullable();
            $table->string('logradouro', 120)->nullable();
            $table->string('cep', 8)->nullable();
            $table->primary('id');
            $table->index('id', 'CD_LOGRADOURO');
            $table->index('cep', 'NewIndex');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logradouros');
    }
};
