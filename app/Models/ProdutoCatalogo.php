<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdutoCatalogo extends Model
{
    use HasFactory;

    protected $table = 'produto_catalogo';

    protected $fillable = [
        'estoque_id',
        'categoria_id',
        'sub_categoria_id',
        'produto_id',
    ];

    public function estoque()
    {
        return $this->belongsTo(Estoque::class);
    }

    public function categoria()
    {
        return $this->belongsTo(Categorias::class);
    }

    public function subCategoria()
    {
        return $this->belongsTo(Categorias::class);
    }

    public function produto()
    {
        return $this->belongsTo(Produtos::class);
    }
}
