<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categorias extends Model
{
    use HasFactory;

    protected $table = 'categorias';

    protected $fillable = [
        'categoria',
        'show_cardpress',
        'sn',
        'id_categoria',
        'descricao',
        'keywords',
        'capa',
        'ativo',
        'hits',
        'ord',
    ];

    protected $casts = [
        'show_cardpress' => 'boolean',
        'id_categoria' => 'integer',
        'ativo' => 'boolean',
    ];

    public function produtos()
    {
        return $this->belongsToMany(Produto::class, 'categorias_produtos', 'id_categoria', 'id_produto');
    }
}
