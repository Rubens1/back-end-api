<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NFE;
use Illuminate\Support\Facades\{
    Hash,
    Validator
};

use Exception;

class NFEController extends Controller
{

    public function index(Request $request)
    {
        $perPage = $request->query("perPage") ?? 10;

        $nfe = NFE::paginate($perPage);
    }

    /**
     * Cadastrar Nfe.
     *
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
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
            'aliquota_simples' => 'string',
            'valorDanfe' => 'string',
            'valorImpostos' => 'string',
            'valorFrete' => 'string',
            'protCancelXML' => 'string',
            'cfop' => 'string',
            'versao' => 'string',
            'datahora_last_update' => 'string',
        ]);

        try {

            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            NFE::create($request->validated());

            return response()->json([
                "status" => "ERROR",
                "message" => "Desculpe estamos enfrentand problemas internos."
            ], 500);
        } catch (Exception $e) {
            return $e;
        }
    }

    /**
     * Obtem a NFE pelo id da nfe
     */
    public function info($id)
    {
        return NFE::findOrfail($id);
    }
}
