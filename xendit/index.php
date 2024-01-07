<?php

$api_key = 'xnd_development_mqnzmzRtctOuJk2k0fW3VcTiLhr3o7VzH0nOrDuCv4D8Ih8aYbjA2hflygiFgz5D';
$base_url = 'https://api.xendit.co';

function createInvoice($amount, $external_id, $payer_email)
{
    global $api_key, $base_url;

    $url = $base_url . '/v2/invoices';
    $headers = array(
        'Content-Type: application/json',
        'Authorization: Basic ' . base64_encode($api_key . ':')
    );

    $data = array(
        'external_id' => $external_id,
        'amount' => $amount,
        'payer_email' => $payer_email,
        'description' => 'Pembayaran menggunakan Xendit'
    );

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);

    if ($httpCode == 200) {
        $invoice_data = json_decode($response, true);
        $invoice_url = $invoice_data['invoice_url'];
        echo 'Invoice URL: ' . $invoice_url;
    } else {
        echo 'Error: ' . $httpCode . ' - ' . $response;
    }
}

// Ganti dengan nilai yang sesuai
$amount = 100000;
$external_id = 'invoice123';
$payer_email = 'customer@example.com';

createInvoice($amount, $external_id, $payer_email);
