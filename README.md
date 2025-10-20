# EduplusPGW PHP SDK

[![License: MIT](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

EduplusPGW is a lightweight PHP SDK for integrating with the Eduplus Payment Gateway (PGW).
It allows you to generate payment URLs and retrieve payment session details easily using Guzzle HTTP.

------------------------------------------------------------
Installation
------------------------------------------------------------

Install via Composer:
```bash
composer require mojahed/edupluspgw
```
------------------------------------------------------------
Usage
------------------------------------------------------------

1. Initialize with API key and create a payment URL:
```php
<?php
use Mojahed\EduplusPGW;

$apiKey = 'your_api_key_here';
$gateway = 'bkash'; // supported gateways: bkash, rocket, etc.

$data = [
    'amount'          => 1000.50,
    'customer_name'   => 'John Doe',
    'customer_phone'  => '017XXXXXXXX',
    'customer_email'  => 'john@example.com',
    'customer_address'=> 'Dhaka, Bangladesh',
    'product_name'    => 'Tuition Fee',
    'reference'       => 'ST12345',
    'callback_url'    => 'https://your-site.com/payment/callback',
    'metadata'        => [
        'student_id' => 'ST12345',
        'year'       => 2025,
        'month'      => '10',
    ]
];

// Create payment URL
$paymentUrl = EduplusPGW::of($apiKey, $gateway)->getPaymentUrl($data);

if ($paymentUrl) {
    echo "Redirect the user to: " . $paymentUrl;
} else {
    print_r(EduplusPGW::of($apiKey, $gateway)->errors);
}
```
------------------------------------------------------------
2. Retrieve Payment Session
------------------------------------------------------------
```php
$sessionId = 'EPGW_SESSION_ID';

$sessionData = EduplusPGW::apiKey($apiKey)
                ->gateway($gateway)
                ->getPaymentSession($sessionId);

if ($sessionData) {
    print_r($sessionData);
} else {
    print_r(EduplusPGW::apiKey($apiKey)->errors);
}
```
------------------------------------------------------------
API Methods
------------------------------------------------------------

- apiKey($apiKey)
  Set your API key.

- of($apiKey, $gateway)
  Convenience method to set API key and gateway in one call.

- gateway($gateway)
  Set the payment gateway (e.g., 'bkash', 'rocket').

- getPaymentUrl(array $data)
  Sends a request to create a payment URL for the specified gateway.
  Returns the URL string if successful, null on failure.

- getPaymentSession(string $session)
  Retrieves payment session details by session ID.
  Returns an array of payment session data if successful, null on failure.

- $errors
  Array containing error messages from the last request.

------------------------------------------------------------
Requirements
------------------------------------------------------------

- PHP 7.4+
- guzzlehttp/guzzle ^7.0

------------------------------------------------------------
Author
------------------------------------------------------------

Md Mojahedul Islam
Email: dev.mojahedul@gmail.com
Website: https://md-mojahed.github.io

------------------------------------------------------------
License
------------------------------------------------------------

MIT License
