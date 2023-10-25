<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdutosFotos extends Model
{
    use HasFactory;

    protected $table = 'produtos_fotos';

    protected $fillable = [
        'id_produto',
        'src',
        'datahora',
        'situacao',
    ];

    // Define the relationship with the 'produtos' table
    public function produto()
    {
        return $this->belongsTo(Produtos::class, 'id_produto');
    }
}
