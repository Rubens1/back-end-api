<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PessoaGrupo extends Model
{
    use HasFactory;
    protected $table = 'pessoa_grupos';

    protected $fillable = [
        'id_pessoa',
        'id_grupo',
        'sessao',
        'array_permissoes',
        'id_pessoa',
        'datahora',
    ];
}
