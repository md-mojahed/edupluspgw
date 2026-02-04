<?php

require_once __DIR__.'/../vendor/autoload.php';

use Mojahed\EduplusPGW;

$apiKey = 'JRSZO%2#68bcF4Ffd9DA7B33320';
$gateway = 'sslcommerz';
$eduplusPGW = EduplusPGW::of($apiKey, $gateway);
$response = $eduplusPGW->getPaymentUrl([
    'amount' => 100,
    'customer_name' => "Md Mojahed",
    'customer_phone' => "01800000000",
    'customer_email' => "email@gmail.com",
    'customer_address' => "Dhaka",
    'callback_url' => "https://google.com",
    'invoice_number' => uniqid(),
    'reference' => "md_mojahed",
    'product_name' => "Tuition Fees",
]);
$url = null;
$session = null;

if($response) {
    $url = $response['payment_url'];
    $session = $response['epgw_session'];
}
print_r([
    $url,
    $session
]);

$paymentSession = $eduplusPGW->getPaymentSession($session);

print_r([
    $paymentSession
]);

$paymentInvoice = $eduplusPGW->getInvoiceDetails($paymentSession['invoice_number']);

print_r([
    $paymentInvoice
]);

$paymentGatewayInvoice = $eduplusPGW->getGatewayInvoice($gateway, $paymentSession['invoice_number']);

print_r([
    $paymentGatewayInvoice
]);

$paymentSessionPosted = $eduplusPGW->markAsPosted($session);

print_r([
    $paymentSessionPosted
]);