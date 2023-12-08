<?php

$url = 'https://matls-clients.api.stage.cora.com.br/token';
$certFile = 'C:\Users\dev03\OneDrive\Documentos\certificate.pem';
$keyFile = 'C:\Users\dev03\OneDrive\Documentos\private-key.key';
$keyPassword = ''; // If your private key is password-protected

$data = [
    'grant_type' => 'client_credentials',
    'client_id' => 'int-lODQwR7VgffEWdYjrxQRs',
];

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSLCERT, $certFile);
curl_setopt($ch, CURLOPT_SSLKEY, $keyFile);
curl_setopt($ch, CURLOPT_SSLKEYPASSWD, $keyPassword);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Set to true for production, false for testing
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

$response = curl_exec($ch);

if ($response === false) {
    // Handle cURL error
    echo 'Curl error: ' . curl_error($ch);
} else {
    // Process the response
    $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    echo 'Status Code: ' . $statusCode . PHP_EOL;
    echo 'Response: ' . $response . PHP_EOL;
}

curl_close($ch);
