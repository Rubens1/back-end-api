<?php

namespace App\Http\Controllers\Pagamento;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use App\Helpers\Payload;
class PixController extends Controller
{
    /**
     * Gera o código para o QRCode
    */
    public function generateQrCode(Request $request)
    {
        
        $payload = (new Payload)->setPixKey(env('PIX'))
                            ->setDescription($request->descricao)
                            ->setMerchantName($request->cliente)
                            ->setMerchantCity($request->cidade)
                            ->setTxId($request->codigo)
                            ->setAmount($request->valor);

        $payloadQrCode = $payload->getPayload();

        return response()->json(['payload' => $payloadQrCode], 200);
    }

    /**
     * Retorno do resultado do pagamento
    */
    public function retornoPix(){

    }
}
