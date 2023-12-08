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
        Schema::create('pessoas_grupos', function (Blueprint $table) {
            $table->id();
            $table->foreignId("id_pessoa")->references("id")->on("pessoas");
            $table->foreignId("id_grupo")->references("id")->on("grupos");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pessoas_grupos');
    }
};
