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
            'base_uri' => "https://pgw.eduplus-bd.com/api"
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
        $this->api = $this->api->withHeaders([
            'api-key' => $this->apiKey
        ]);
        return $this;
    }

    public function gateway($gateway)
    {
        $this->gatewayKey = $gateway;
        return $this;
    }

    public function getPaymentUrl(array $data)
    {
        $response = $this->api->post("/create/{$this->gatewayKey}/payment-url", [
            'json' => $data
        ]);

        if($response->getStatusCode() === 200) {
            $data = json_decode($response->getBody()->getContents(), true) ?? [];

            if($data['status'] == 'success') {
                return $data['payment_url'];
            }
            $this->errors[] = $data['message'] ?? "Request failed! status code: " . $response->getStatusCode();
            return null;
        }
        $this->errors[] = "Request failed! status code: ". $response->getStatusCode();
        return null;
    }

    public function getPaymentSession(string $session)
    {
        $response = $this->api->get("/session/{$session}");

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
}