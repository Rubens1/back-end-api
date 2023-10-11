<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpcoesPrazoPagamento extends Model
{
    use HasFactory;

    protected $table = 'op_prazo_pagto';

    protected $primaryKey = 'id';

    protected $fillable = [
        'opcao',
        'detalhes',
    ];
}
