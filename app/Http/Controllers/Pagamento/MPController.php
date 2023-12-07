<?php

namespace App\Http\Controllers\Pagamento;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\Exceptions\MPApiException;
use MercadoPago\MercadoPagoConfig;

class MPController extends Controller
{
    private $client;
    public function __construct()
    {
        MercadoPagoConfig::setAccessToken('TEST-2989367700046971-042113-08c0f8928f3f9ecc6b712846cab0e5da-220350751');
        $this->client = new PaymentClient();

    }

    public function proccessPayment(Request $request)
    {

        try {

            $data = json_decode($request->body);

            $amount = number_format($data->transaction_amount, 2);

            $request_mp = [
                "transaction_amount" => floatval($amount),
                "token" => $data->token,
                //"description" => $data->description,
                "installments" => $data->installments,
                "payment_method_id" => $data->payment_method_id,
                "payer" => [
                    "email" => $data->payer->email
                ]
            ];


            $payment = $this->client->create($request_mp);

            return response()->json(["payment_id" => $payment]);

            // Step 6: Handle exceptions
        } catch (MPApiException $e) {
            return response($e->getApiResponse()->getContent(), $e->getApiResponse()->getStatusCode());
            //echo "Content: " . $e->getApiResponse()->getContent() . "\n";
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
}
