<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bancos extends Model
{
    use HasFactory;
    
    protected $table = 'bancos';

    protected $fillable = [
        "pix",
        "agencia",
        "conta",
        "validade",
        "id_pessoa"
    ];

    public function pessoas()
    {
        return $this->belongsTo(Pessoas::class, 'id_pessoa');
    }
}
