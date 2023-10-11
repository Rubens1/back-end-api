<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdutosInfo extends Model
{
    use HasFactory;

    protected $table = 'produtos_info'; // Specify the table name if it's different from the model name.

    protected $fillable = [
        'titulo',
        'info',
        'data_cadastro',
        'id_produto',
    ];

    protected $dates = ['data_cadastro'];

    public function produto()
    {
        return $this->belongsTo(Produto::class, 'id_produto');
    }
}
