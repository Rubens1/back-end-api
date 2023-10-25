<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdutosLoja extends Model
{
    use HasFactory;

    protected $table = 'produtos_lojas'; // Specify the table name if it's different from the model name.

    protected $fillable = [
        'id_loja',
        'datahora',
        'id_produto',
    ];

    protected $dates = ['datahora'];

    public function produto()
    {
        return $this->belongsTo(Produtos::class, 'id_produto');
    }
}
