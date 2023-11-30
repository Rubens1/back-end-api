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
        Schema::create('blog', function (Blueprint $table) {
            $table->id();
            $table->foreignId("id_tag")->references("id")->on("tags");
            $table->foreignId("id_post")->references("id")->on("posts");
            $table->longText('keywords')->nullable();
            $table->longText('seo_descricao')->nullable();
            $table->string('url')->nullable();
            $table->timestamps();
        });
    }

   
};
