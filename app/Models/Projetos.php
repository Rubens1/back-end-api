<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Projetos extends Model
{
    use HasFactory;

    protected $fillable = [
        "projeto_uuid",
        "nome_projeto",
        "canvas_json",
        "imagens",
        "id_pessoa"
    ];
}
