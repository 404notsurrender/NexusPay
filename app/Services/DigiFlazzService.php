<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class DigiFlazzService
{
    protected $username;
    protected $apiKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->username = env('DIGIFLAZZ_USERNAME');
        $this->apiKey = env('DIGIFLAZZ_API_KEY');
        $this->baseUrl = env('DIGIFLAZZ_BASE_URL', 'https://api.digiflazz.com/v1');
    }

    public function getProducts()
    {
        $response = Http::post($this->baseUrl . '/price-list', [
            'cmd' => 'prepaid',
            'username' => $this->username,
            'sign' => md5($this->username . $this->apiKey . 'pricelist'),
        ]);

        return $response->json();
    }

    public function placeOrder($productCode, $customerNo, $refId)
    {
        $response = Http::post($this->baseUrl . '/transaction', [
            'username' => $this->username,
            'buyer_sku_code' => $productCode,
            'customer_no' => $customerNo,
            'ref_id' => $refId,
            'sign' => md5($this->username . $this->apiKey . $refId),
        ]);

        return $response->json();
    }

    public function checkStatus($refId)
    {
        $response = Http::post($this->baseUrl . '/transaction', [
            'username' => $this->username,
            'ref_id' => $refId,
            'sign' => md5($this->username . $this->apiKey . $refId),
        ]);

        return $response->json();
    }
}
