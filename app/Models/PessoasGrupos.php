<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PessoasGrupos extends Model
{
    use HasFactory;
    protected $table = "pessoas_grupos";

    protected $fillable = [
        "id_pessoa",
        "id_grupo"
    ];

    public function pessoas()
    {
        return $this->belongsToMany(Pessoas::class, 'id_pessoa');
    }
    
}
