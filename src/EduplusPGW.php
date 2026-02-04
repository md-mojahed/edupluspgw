<?php

namespace Mojahed;

use GuzzleHttp\Client;

class EduplusPGW {
    protected Client $api;
    protected $apiKey = null;
    protected $gatewayKey = null;
    public $errors = [];

    public function __construct()
    {
        $this->api = new Client([
            'base_uri' => "https://pgw.eduplus-bd.com"
        ]);
    }

    public static function apiKey($apiKey)
    {
        return (new EduplusPGW())->setApiKey($apiKey);
    }

    public static function of($apiKey, $gateway)
    {
        return EduplusPGW::apiKey($apiKey)->gateway($gateway);
    }

    public function setApiKey($apiKey = null)
    {
        $this->apiKey = $apiKey;
        return $this;
    }

    public function gateway($gateway)
    {
        $this->gatewayKey = $gateway;
        return $this;
    }

    public function getPaymentUrl(array $data)
    {
        $response = $this->api->post("/api/create/{$this->gatewayKey}/payment-url", [
            'json' => $data,
            'headers' => [
                'api-key' => $this->apiKey,
                'Accept' => 'application/json',
            ]
        ]);

        if($response->getStatusCode() === 200) {
            $data = json_decode($response->getBody()->getContents(), true) ?? [];

            if(isset($data['status'])) {
                return $data;
            }
            $this->errors[] = $data['message'] ?? "Request failed! status code: " . $response->getStatusCode();
            return null;
        }
        $this->errors[] = "Request failed! status code: ". $response->getStatusCode();
        return null;
    }

    public function getPaymentSession(string $session)
    {
        $response = $this->api->get("/api/session/{$session}", [
            'headers' => [
                'api-key' => $this->apiKey,
                'Accept' => 'application/json',
            ]
        ]);

        if ($response->getStatusCode() === 200) {
            $data = json_decode($response->getBody()->getContents(), true) ?? [];

            if ($data['status'] == 'success') {
                return $data['payment_session'];
            }
            $this->errors[] = $data['message'] ?? "Request failed! status code: " . $response->getStatusCode();
            return null;
        }
        $this->errors[] = "Request failed! status code: " . $response->getStatusCode();
        return null;
    }

    public function getInvoiceDetails($invoice)
    {
        $response = $this->api->get("/api/invoice/{$invoice}", [
            'headers' => [
                'api-key' => $this->apiKey,
                'Accept' => 'application/json',
            ]
        ]);

        if ($response->getStatusCode() === 200) {
            $data = json_decode($response->getBody()->getContents(), true) ?? [];

            if ($data['status'] == 'success') {
                return $data['payment_session'];
            }
            $this->errors[] = $data['message'] ?? "Request failed! status code: " . $response->getStatusCode();
            return null;
        }
        $this->errors[] = "Request failed! status code: " . $response->getStatusCode();
        return null;
    }

    public function getGatewayInvoice($gateway, $invoice)
    {
        $response = $this->api->get("/api/{$gateway}/invoice/{$invoice}", [
            'headers' => [
                'api-key' => $this->apiKey,
                'Accept' => 'application/json',
            ]
        ]);

        if ($response->getStatusCode() === 200) {
            $data = json_decode($response->getBody()->getContents(), true) ?? [];

            if ($data['status'] == 'success') {
                return $data['payment_data'];
            }
            $this->errors[] = $data['message'] ?? "Request failed! status code: " . $response->getStatusCode();
            return null;
        }
        $this->errors[] = "Request failed! status code: " . $response->getStatusCode();
        return null;
    }

    public function markAsPosted(string $session)
    {
        $response = $this->api->post("/api/session/{$session}/posted", [
            'headers' => [
                'api-key' => $this->apiKey,
                'Accept' => 'application/json',
            ]
        ]);

        if ($response->getStatusCode() === 200) {
            $data = json_decode($response->getBody()->getContents(), true) ?? [];

            if ($data['status'] == 'success') {
                return true;
            }
            $this->errors[] = $data['message'] ?? "Request failed! status code: " . $response->getStatusCode();
            return false;
        }
        $this->errors[] = "Request failed! status code: " . $response->getStatusCode();
        return false;
    }
}
