<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Nfe;
use Illuminate\Support\Facades\{Hash, Validator};

class NfeController extends Controller
{
    /**
     * Lista Nfe.
     *
     */
    public function lista() {
        return Nfe::all();
    }

    /**
     * Registrar Nfe.
     *
     */
    public function registrar(Request $request) {
        $request->validate([
            'id_danfe_remote' => 'integer',
            'id_pedido' => 'integer',
            'chave' => 'string',
            'amb' => 'integer',
            'nfe' => 'integer',
            'digVal' => 'string',
            'cStat' => 'integer',
            'xMotivo' => 'string',
            'nProt' => 'string',
            'dhRecbto' => 'string',
            'nRec' => 'string',
            'datahora' => 'string',
            'pdfDanfe' => 'string',
            'xmlProt' => 'string',
            'xJust' => 'string',
            'regApuracao' => 'string',
            'iss' => 'string',
            'pis' => 'string',
            'cofins' => 'string',
            'csll' => 'string',
            'irpj' => 'string',
            'cpp' => 'string',
            'icms' => 'integer',
            'aliquota_simples' => 'numeric',
            'valorDanfe' => 'numeric',
            'valorImpostos' => 'numeric',
            'valorFrete' => 'numeric',
            'protCancelXML' => 'string',
            'cfop' => 'string',
            'versao' => 'string',
            'datahora_last_update' => 'string',            
        ]);
        try{
            return Nfe::create($request->all());
        }catch(Error $e){
            return $e;
        }
    }

     /**
     * Ver um regsitro de Nfe.
     *
     */
    public function info($id) {
        return Nfe::findOrfail($id);
    }
}
