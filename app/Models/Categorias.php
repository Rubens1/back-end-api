<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\SlugHelper;

class Categorias extends Model
{
    use HasFactory, SlugHelper;

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
        'url'
    ];

    protected $casts = [
        'show_cardpress' => 'boolean',
        'id_categoria' => 'integer',
        'ativo' => 'boolean',
    ];

    public function produtos()
    {
        return $this->belongsToMany(Produtos::class, 'categorias_produtos', 'id_categoria', 'id_produto');
    }
}
