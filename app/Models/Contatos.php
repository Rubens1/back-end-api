<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contatos extends Model
{
    use HasFactory;
    protected $table = 'contatos';

    protected $fillable = [
        "principal",
        "email",
        "telefone",
        "celular",
        "id_pessoa"
    ];
    public function pessoas()
    {
        return $this->belongsTo(Pessoas::class, 'id_pessoa');
    }
}
