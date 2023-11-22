<?php

namespace App\Http\Controllers\Pagamento;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use \MercadoPago\Client\Payment\PaymentClient;
use \MercadoPago\Exceptions\MPApiException;
use \MercadoPago\MercadoPagoConfig;


class CartaoController extends Controller
{
    /**
     * Pagamento em cartão de depito ou credito do mercado pago
     */
    public function mercadoPago(Request $request)
    {

        MercadoPagoConfig::setAccessToken("TEST-2989367700046971-042113-08c0f8928f3f9ecc6b712846cab0e5da-220350751");
        $client = new PaymentClient();

        try {
            $payment = $client->create($request->input());
            dd($payment);
            echo $payment->id;

            // Step 6: Handle exceptions
        } catch (MPApiException $e) {
            echo "Status code: " . $e->getApiResponse()->getStatusCode() . "\n";
            dd($e->getApiResponse()->getContent());
        } catch (\Exception $e) {
            return response()->json(["d" => $e->getMessage()]);
        }

    }


}