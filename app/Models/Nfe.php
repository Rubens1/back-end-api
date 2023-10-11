<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nfe extends Model
{

    use HasFactory;
    protected $table = "nfe";
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
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


    /**
     * Timestamps foi setado como falso.
     *
     */
    public $timestamps = false;

}
