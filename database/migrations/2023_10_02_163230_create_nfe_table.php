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
        Schema::create('nfe', function (Blueprint $table) {
            $table->id();
            $table->integer('id_danfe_remote')->nullable();
            $table->integer('id_pedido')->nullable();
            $table->string('chave', 255)->unique();
            $table->integer('amb')->default(1);
            $table->integer('nfe');
            $table->string('digVal')->nullable();
            $table->integer('cStat')->nullable();
            $table->string('xMotivo')->nullable();
            $table->string('nProt')->nullable();
            $table->string('dhRecbto')->nullable();
            $table->string('nRec')->nullable();
            $table->datetime('datahora')->nullable();
            $table->string('pdfDanfe')->nullable();
            $table->string('xmlProt')->nullable();
            $table->string('xJust')->nullable();
            $table->enum('regApuracao', ['SIMPLES', 'REAL', 'PRESUMIDO'])->nullable();
            $table->integer('iss')->nullable();
            $table->integer('pis')->nullable();
            $table->integer('cofins')->nullable();
            $table->integer('csll')->nullable();
            $table->integer('irpj')->nullable();
            $table->string('cpp')->nullable();
            $table->integer('icms')->nullable();
            $table->decimal('aliquota_simples', 10, 2)->nullable();
            $table->decimal('valorDanfe', 10, 2)->nullable();
            $table->decimal('valorImpostos', 10, 2)->nullable();
            $table->decimal('valorFrete', 10, 2)->nullable();
            $table->string('protCancelXML')->nullable();
            $table->string('cfop', 8)->nullable();
            $table->string('versao')->nullable();
            $table->datetime('datahora_last_update')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nfe');
    }
};
