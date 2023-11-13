<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PessoasContas extends Model
{
    use HasFactory;

    protected $table = "pessoas_contas";

    protected $fillable = [
        "is_main",
        "tipo_conta",
        "agencia",
        "conta",
        "chave_pix",
        "id_pessoa",

    ];

    public function pessoas()
    {
        return $this->belongsTo(Pessoas::class);
    }
}
