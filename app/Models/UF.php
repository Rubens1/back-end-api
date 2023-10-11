<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UF extends Model
{
    use HasFactory;

    protected $table = 'uf';

    protected $primaryKey = 'id';

    protected $fillable = [
        'uf',
        'id_capital',
        'regiao',
        'nome',
        'ref',
    ];

    public $timestamps = false;
}
