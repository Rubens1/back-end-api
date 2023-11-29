<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blogs extends Model
{
    use HasFactory;

    protected $table = 'posts';

    protected $fillable = [
        "titulo",
        "descricao",
        "keywords",
        "seo_descricao",
        "capa",
        "url"
    ];
}
