<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bairros extends Model
{
    use HasFactory;

    protected $table = 'bairros';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id_cidade',
        'bairro',
    ];

    public function cidade()
    {
        return $this->belongsTo(Cidades::class, 'id_cidade');
    }
}
