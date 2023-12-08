<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePessoasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pessoas', function (Blueprint $table) {
            $table->id();
            $table->integer('id_pai')->nullable();
            $table->boolean('cadastro_ativo')->default(1);
            $table->string('codigo', 8)->nullable();
            $table->string('nome', 100);
            $table->boolean("verificou_senha")->nullable()->default(true);
            $table->integer("is_client")->nullable()->default(1);
            $table->boolean("is_afiliado")->nullable()->default(0);
            $table->boolean("is_partner")->nullable()->default(0);
            $table->string('alias', 120)->nullable();
            $table->string('email', 60)->unique();
            $table->string('signature_email', 50)->nullable()->unique();
            $table->string('situacao')->default(1);
            $table->string('cpfcnpj', 100)->nullable()->unique();
            $table->string('telefone', 18)->nullable();
            $table->string('celular', 18)->nullable();
            $table->string('razao_social', 60)->nullable();
            $table->enum('tipo', ['PF', 'PJ'])->nullable();
            $table->string('cpf_responsavel', 18)->nullable();
            $table->string('foto', 120)->nullable();
            $table->string('ie', 18)->nullable();
            $table->date('data_nasc')->nullable();
            $table->enum('sexo', ['M', 'F'])->nullable();
            $table->string('rg', 20)->nullable();
            $table->string('orgao_emissor', 30)->nullable();
            $table->enum('estado_civil', ['Solteiro (a)', 'Casado (a)', 'Viuvo (a)', 'Divorciado (a)'])->nullable();
            $table->text('obs')->nullable();
            $table->string('cod_rec', 250)->nullable();
            $table->integer('id_endereco_fiscal')->nullable();
            $table->integer('id_cliente')->nullable();
            $table->string('signature_pwd', 50)->nullable();
            $table->string('senha')->nullable();
            $table->tinyInteger('comiss_elegivel')->default(0);
            $table->integer("permissoes")->default(3);
            $table->string('ccm', 15)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pessoas');
    }
}
