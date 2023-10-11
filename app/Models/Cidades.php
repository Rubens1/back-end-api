<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cidades extends Model
{
    use HasFactory;

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

    public function subcategorias()
    {
        return $this->hasMany(Subcategoria::class, 'id_categoria');
    }
}
