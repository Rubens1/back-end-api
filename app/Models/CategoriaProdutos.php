<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoriaProdutos extends Model
{
    use HasFactory;
    protected $table = 'categorias_produtos';

    protected $primaryKey = null;

    public $incrementing = false;

    protected $hidden = [
        "created_at",
        "updated_at"
    ];
    
    public function produtos()
    {
        return $this->belongsTo(Produtos::class, 'id_produto', 'id');
    }

    public function categoria()
    {
        return $this->belongsTo(Categorias::class, 'id_categoria', 'id');
    }

}
