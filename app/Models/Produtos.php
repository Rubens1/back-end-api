<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produtos extends Model
{
    use HasFactory;

    protected $table = 'produtos';

    protected $fillable = [
        'id_especializacao',
        'ativo',
        'sn',
        'codigo',
        'id_produto',
        'nome',
        'src_alt',
        'proporcao_venda',
        'proporcao_consumo',
        'id_exclusivo',
        'use_frete',
        'prazo',
        'show_in_cardpress',
        'src',
        'qminima',
        'keywords',
        'descricao',
        'long_description',
        'ficha_tecnica',
        'itens_inclusos',
        'especificacoes',
        'opcoes',
        'opcoes_3',
        'opcoes_2',
        'altura',
        'peso',
        'largura',
        'comprimento',
        'origem',
        'sub_tributaria',
        'origem_noie',
        'aliquota',
        'publicar',
        'hits',
        'id_foto_default',
        'id_fabricante',
        'id_produto_avulso',
        'demo',
        'gabarito',
    ];

    protected $hidden = [
        "updated_at",
        "created_at"
    ];
}

