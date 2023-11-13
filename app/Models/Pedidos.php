<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedidos extends Model
{
    use HasFactory;

    protected $table = 'pedidos';

    protected $fillable = [
        "id",
        'id_pedido',
        'id_contrato',
        'id_bureau',
        'tipo',
        'custo_total',
        'custo_frete',
        'sit_pagto',
        'sit_pedido',
        'sit_entrega',
        'sit_producao',
        'vencimento',
        'data_pagto',
        'datahora',
        'validade_proposta',
        'prazo_producao',
        'comissoes_pagas',
        'obs',
        'file_cotacao',
        'file_pedido',
        'royalties_pagos',
        'priority',
        'id_cliente',
        'id_op_frete',
        'id_op_pagto',
        'id_endereco',
        'id_pessoa',
        'id_vendedor',
    ];

    protected $dates = [
        'vencimento',
        'data_pagto',
        'datahora',
        'validade_proposta',
    ];

    public function cliente()//ok
    {
        return $this->belongsTo(Pessoas::class, 'id_cliente');
    }

    public function opcaoFrete()//ok
    {
        return $this->belongsTo(OpcoesFrete::class, 'id_op_frete');
    }

    public function opcaoPagamento()//ok
    {
        return $this->belongsTo(OpcoesPagamento::class, 'id_op_pagto');//ok
    }

    public function endereco()//
    {
        return $this->belongsTo(PessoasEndereco::class, 'id_endereco');//OK
    }

    public function vendedor()
    {
        return $this->belongsTo(Pessoas::class, 'id_vendedor');//ok
    }
}
