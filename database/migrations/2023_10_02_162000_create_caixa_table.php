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
        Schema::create('caixa', function (Blueprint $table) {
            $table->id();
            $table->integer('id_pessoa')->unsigned()->nullable();
            $table->integer('id_origem')->nullable();
            $table->integer('id_pedido')->unsigned()->nullable();
            $table->string('codigo', 35)->nullable();
            $table->datetime('datahora')->nullable();
            $table->decimal('valor', 10, 2)->nullable();
            $table->integer('op')->nullable();
            $table->string('obs', 250)->nullable();
            $table->enum('situacao', ['BL', 'DS'])->default('DS');
            $table->string('ip', 18)->nullable();
            $table->timestamps();
            $table->unique('id');
            $table->unique(['id_pessoa', 'id_pedido', 'valor', 'codigo'], 'UK_caixa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('caixa');
    }
};
