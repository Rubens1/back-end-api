<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstoqueEventos extends Model
{
    use HasFactory;

    protected $table = 'estoque_eventos';

    protected $fillable = [
        'evento',
        'acao',
        'tipo_evento',
        'who',
        'to_show',
        'requires',
        'hide',
        'descricao',
    ];
}
