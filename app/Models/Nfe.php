<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NFE extends Model
{
    use HasFactory;

    protected $table = 'nfe';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id_danfe_remote',
        'id_pedido',
        'chave',
        'amb',
        'nfe',
        'digVal',
        'cStat',
        'xMotivo',
        'nProt',
        'dhRecbto',
        'nRec',
        'datahora',
        'pdfDanfe',
        'xmlProt',
        'xJust',
        'regApuracao',
        'iss',
        'pis',
        'cofins',
        'csll',
        'irpj',
        'cpp',
        'icms',
        'aliquota_simples',
        'valorDanfe',
        'valorImpostos',
        'valorFrete',
        'protCancelXML',
        'cfop',
        'versao',
        'datahora_last_update',
    ];

    protected $casts = [
        'amb' => 'integer',
        'nfe' => 'integer',
        'cStat' => 'integer',
    ];

    protected $dates = [
        'datahora',
        'datahora_last_update',
    ];
}
