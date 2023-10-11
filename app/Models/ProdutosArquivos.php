<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdutosArquivos extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'src',
        'obs',
        'datahora',
        'size',
        'downloads',
        'situacao',
        'id_produto',
    ];

    protected $casts = [
        'downloads' => 'integer',
        'situacao' => 'integer',
    ];

    public function produto()
    {
        return $this->belongsTo(Produto::class, 'id_produto');
    }
}
