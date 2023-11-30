<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blogs extends Model
{
    use HasFactory;

    protected $table = 'blog';

    protected $fillable = [
        "id_post",
        "id_tag",
        "view",
        "keywords",
        "seo_descricao",
        "url"
    ];

    public function tags()
    {
        return $this->belongsTo(Tags::class, 'id_tag');
    }

    public function posts()
    {
        return $this->belongsTo(Posts::class, 'id_post');
    }
}
