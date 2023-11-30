<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Posts extends Model
{
    use HasFactory;

    protected $table = 'posts';

    protected $fillable = [
        "titulo",
        "descricao",
        "keywords",
        "seo_descricao",
        "capa",
        "id_pessoa",
        "url"
    ];

    public function pessoas()
    {
        return $this->belongsTo(Pessoas::class, 'id_pessoa');
    }
}
