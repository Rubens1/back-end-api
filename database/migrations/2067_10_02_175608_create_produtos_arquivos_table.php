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
        Schema::create('produtos_arquivos', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 60)->nullable();
            $table->string('src', 240)->nullable();
            $table->text('obs')->nullable();
            $table->datetime('datahora')->nullable();
            $table->string('size', 10)->default('0');
            $table->integer('downloads')->default(0);
            $table->integer('situacao')->default(0);
            $table->foreignId('id_produto')->references('id')->on('produtos')->onDelete('cascade');
            $table->timestamps(); // Adds 'created_at' and 'updated_at' columns
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('arquivos');
    }
};
