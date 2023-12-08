<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PedidosPagamento extends Model
{
    use HasFactory;

    protected $table = 'pedidos_pagto';

    protected $fillable = [
        'vencimento',
        'valor',
        'situacao',
        'parcela',
    ];

    protected $dates = [
        'vencimento',
    ];

    public function pedido()
    {
        return $this->belongsTo(Pedidos::class, 'id_pedido');
    }
}
