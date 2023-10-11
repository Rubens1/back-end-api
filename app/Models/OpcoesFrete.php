<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpcoesFrete extends Model
{
    use HasFactory;

    protected $table = 'op_frete';

    protected $primaryKey = 'id';

    protected $fillable = [
        'frete',
        'funcao',
        'descricao',
        'codigo',
        'id_servico',
        'cartao_postagem',
        'cod_contrato',
        'cod_administrativo',
        'ativo',
        'pub_ativo',
    ];

    protected $casts = [
        'id_servico' => 'integer',
        'ativo' => 'integer',
        'pub_ativo' => 'integer',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];
}
