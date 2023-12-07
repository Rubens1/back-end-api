<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    protected $table = 'blog';

    protected $fillable = [
        "id_post",
        "id_tag",
        "keywords",
        "seo_descricao",
        "url"
    ];

    public function posts()
    {
        return $this->belongsTo(Posts::class, 'id_post');
    }
}