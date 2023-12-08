<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdutosClientes extends Model
{
    use HasFactory;

    protected $table = 'produtos_clientes';

    protected $fillable = [
        'datahora',
        'obs',
        'preco',
        'img',
        'id_pessoa',
        'id_produto',
    ];

    protected $casts = [
        'preco' => 'decimal:2',
    ];

    public function pessoa()
    {
        return $this->belongsTo(Pessoas::class, 'id_pessoa');
    }

    public function produto()
    {
        return $this->belongsTo(Produto::class, 'id_produto');
    }
}
