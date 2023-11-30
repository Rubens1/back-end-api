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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('titulo')->nullable();
            $table->longText('descricao')->nullable();
            $table->longText('keywords')->nullable();
            $table->longText('seo_descricao')->nullable();
            $table->string('capa')->nullable();
            $table->string('url')->nullable();
            $table->foreignId("id_pessoa")->references("id")->on("pessoas");
            $table->timestamps();
        });
    }
    
};
