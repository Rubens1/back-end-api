<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalhesProduto extends Model
{
    use HasFactory;

    protected $table = 'detalhes_produtos';

    protected $fillable = [
        'preco',
        'datahora',
        'codigo',
        'grupo',
        'nome_nfs',
        'nome_danfe',
        'ncm',
        'prazo',
        'comissao',
        'royaties',
        'id_produto',
    ];

    protected $casts = [
        'preco' => 'decimal:2',
        'comissao' => 'decimal:2',
        'royaties' => 'decimal:2',
    ];

    public function produto()
    {
        return $this->belongsTo(Produtos::class, 'id_produto');
    }   
}
