<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PessoasEndereco extends Model
{
    use HasFactory;

    protected $table = 'pessoas_enderecos';

    protected $fillable = [
        "nome",
        "id_pessoa",
        'situacao',
        'filial',
        'logradouro',
        'numero',
        'complemento',
        'bairro',
        'cep',
        'cidade',
        'cod_municipio',
        'estado',
        'responsavel',
        'rg_responsavel',
        'ip_cadastro',
        'obs',
        'active',
        'favorite',
        'id_usuario',
        'cod_uf',
        'fone',
        'retirada',
        'impressao',
        'material',
        'venda',
        'layout',
        'faz_entrega',
        'tx_entrega',
        'km_lim_entrega',
        'foto',
        'recebe_dinheiro',
        'km_lim_entrega_ex',
        'tx_entrega_ex',
        'referencia',
        'id_matriz',
        'faz_entrega_ex',
    ];

    protected $casts = [
        'favorite' => 'boolean',
    ];

    public function pessoa()
    {
        return $this->belongsTo(Pessoa::class, 'id_pessoa');
    }
}
