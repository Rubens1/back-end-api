<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Artigos extends Model
{
    use HasFactory;

    protected $table = 'artigos';

    protected $fillable = [
        'id_pessoa',
        'titulo',
        'img',
        'texto',
        'ativo',
        'datahora',
        'intro',
        'keywords',
        'datahora_update',
        'hits',
    ];

    public function pessoa()
    {
        return $this->belongsTo(Pessoas::class);
    }
}
