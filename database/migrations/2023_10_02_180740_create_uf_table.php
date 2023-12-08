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
        Schema::create('uf', function (Blueprint $table) {
            $table->integer('id')->unsigned();
            $table->string('uf', 3);
            $table->integer('id_capital')->unsigned();
            $table->string('regiao', 15)->nullable();
            $table->string('nome', 50)->nullable();
            $table->string('ref', 10)->nullable();
            $table->primary('id');
            $table->unique('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produtos_uf');
    }
};
