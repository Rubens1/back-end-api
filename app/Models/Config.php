<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    use HasFactory;

    protected $table = 'cidades';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id_uf',
        'cidade',
        'cod_municipio',
        'uf',
    ];

    public function uf()
    {
        return $this->belongsTo(Uf::class, 'id_uf');
    }
}
