<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpcoesPagamento extends Model
{
    use HasFactory;

    protected $table = 'op_pagto';

    protected $primaryKey = 'id';

    protected $fillable = [
        'nome',
        'titulo',
        'ativo',
        'codigo_nf',
        'descricao',
        'pub_ativo',
    ];

    protected $casts = [
        'ativo' => 'integer',
        'pub_ativo' => 'integer',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];
}
