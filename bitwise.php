<?php
require_once('vendor/autoload.php');

$client = new \GuzzleHttp\Client();

$response = $client->request('POST', 'https://api.stage.cora.com.br/invoices/', [
  'body' => '{"code":"meu_id","customer":{"name":"Fulano da Silva","e-mail":"fulano@email.com","document":{"identity":"34052649000178","type":"CNPJ"},"address":{"street":"Rua Gomes de Carvalho","number":"1629","district":"Vila Olímpia","city":"São Paulo","state":"SP","complement":"N/A","zip_code":"00111222"}},"payment_terms":{"due_date":"2023-12-12","fine":{"amount":200},"discount":{"type":"PERCENT","value":10}},"payment_forms":["PIX"]}',
  'headers' => [
    'Idempotency-Key' => 'int-lODQwR7VgffEWdYjrxQRs',
    'accept' => 'application/json',
    'content-type' => 'application/json',
  ],
]);

echo $response->getBody();