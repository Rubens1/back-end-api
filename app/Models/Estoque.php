<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estoque extends Model
{
    use HasFactory;

    protected $table = 'estoque';

    protected $fillable = [
        'id_produto',
        'id_origem',
        'id_destino',
        'id_pedido',
        'qtd',
        'id_pedido_item',
        'id_compra',
        'opt',
        'datahora',
        'obs',
        'finalidade',
        'grupo',
        'id_agente',
        'datahora_reg',
        'id_agente_remocao',
        'removido',
        'datahora_remocao',
        'nf',
        'id_nf',
        'preco_compra',
        'preco_vendido',
        'sn',
    ];

    protected $dates = ['datahora', 'datahora_reg', 'datahora_remocao'];

    public function evento()
    {
        return $this->belongsTo(EstoqueEvento::class, 'id_evento');
    }

    public function pessoa()
    {
        return $this->belongsTo(Pessoas::class, 'id_pessoa');
    }

    public function produto()
    {
        return $this->belongsTo(Produtos::class, 'id_produto');
    }
}
