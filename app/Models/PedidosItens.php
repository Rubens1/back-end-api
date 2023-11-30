<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PedidosItens extends Model
{
    use HasFactory;

    protected $table = 'pedidos_itens';

    protected $fillable = [
        'numero_pedido',
        'id_pedido',
        'situacao',
        'item_data',
        'qtd',
        'total',
        'preco',
        'valor_royalties',
        'perc_royalties',
        'royalties_pagos',
        'perc_comissao',
        'tipo_cartao',
        'coresFrente',
        'coresVerso',
        'custoProducao',
        'repasseServico',
        'repasseManutencao',
        'custoColor',
        'id_produto',
        'id_produto_estoque',
        'preco_compra',
        'valor_comissao',
        'creditado',
        'bx_estoque',
    ];

    protected $casts = [
        'royalties_pagos' => 'boolean',
        'creditado' => 'boolean',
        'bx_estoque' => 'boolean',
    ];

    protected $dates = [
        'item_data',
    ];

    public function produto()
    {
        return $this->belongsTo(Produtos::class, 'id_produto');
    }

    public function pedido()
    {
        return $this->belongsTo(Pedidos::class, 'id_pedido');
    }
}
