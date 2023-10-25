<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Caixa extends Model
{
    use HasFactory;

    protected $table = 'caixa';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id_pessoa',
        'id_origem',
        'id_pedido',
        'codigo',
        'datahora',
        'valor',
        'op',
        'obs',
        'situacao',
        'ip',
    ];

    public function pessoa()
    {
        return $this->belongsTo(Pessoas::class, 'id_pessoa');
    }

    public function pedido()
    {
        return $this->belongsTo(Pedidos::class, 'id_pedido');
    }
}
