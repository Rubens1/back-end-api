<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdutoFornecedores extends Model
{
    use HasFactory;

    protected $table = 'produto_fornecedores';

    protected $fillable = [
        'preco',
        'datahora',
        'id_produto',
        'id_pessoa',
    ];

    protected $casts = [
        'preco' => 'decimal:2',
    ];

    public function produto()
    {
        return $this->belongsTo(Produtos::class, 'id_produto');
    }

    public function pessoa()
    {
        return $this->belongsTo(Pessoas::class, 'id_pessoa');
    }
}
