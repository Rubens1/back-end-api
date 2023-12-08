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
        Schema::create('produtos_fotos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_produto')->nullable();
            $table->string('src', 255)->nullable();
            $table->datetime('datahora')->nullable();
            $table->tinyInteger('situacao')->default(1);
            $table->timestamps();

            $table->foreign('id_produto')->references('id')->on('produtos')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fotos_produto');
    }
};
