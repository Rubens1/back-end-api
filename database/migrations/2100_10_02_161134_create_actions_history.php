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
        
        Schema::create('actions_history', function (Blueprint $table) {
            $table->id();
            $table->string('ip', 18)->nullable();
            $table->string('obs', 255)->nullable()->default('NULL');
            $table->foreignId('id_action')->nullable()->references('id')->on('actions')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('id_pedido')->nullable()->references('id')->on('pedidos')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('id_agente')->nullable()->references('id')->on('pessoas')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('id_pessoa')->nullable()->references('id')->on('pessoas')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('id_produto')->nullable()->references('id')->on('produtos')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();

            
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('actions_history');
    }
};
