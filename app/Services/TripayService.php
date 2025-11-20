<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TripayService
{
    protected $apiKey;
    protected $privateKey;
    protected $merchantCode;
    protected $baseUrl;

    public function __construct()
    {
        $this->apiKey = env('TRIPAY_API_KEY');
        $this->privateKey = env('TRIPAY_PRIVATE_KEY');
        $this->merchantCode = env('TRIPAY_MERCHANT_CODE');
        $this->baseUrl = env('TRIPAY_BASE_URL', 'https://tripay.co.id/api-sandbox');
    }

    public function getPaymentChannels()
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
        ])->get($this->baseUrl . '/merchant/payment-channel');

        return $response->json();
    }

    public function createTransaction($order, $paymentMethod, $customerEmail = null)
    {
        $payload = [
            'method' => $paymentMethod->code,
            'merchant_ref' => 'ORDER-' . $order->id,
            'amount' => $order->total_price,
            'customer_name' => $order->user ? $order->user->name : 'Guest',
            'customer_email' => $customerEmail ?: ($order->user ? $order->user->email : 'guest@example.com'),
            'order_items' => [
                [
                    'sku' => $order->product->code,
                    'name' => $order->product->name,
                    'price' => $order->total_price,
                    'quantity' => $order->quantity,
                ]
            ],
            'return_url' => env('APP_URL') . '/payment/success',
            'expired_time' => (time() + (24 * 60 * 60)), // 24 hours
            'signature' => hash_hmac('sha256', $this->merchantCode . 'ORDER-' . $order->id . $order->total_price, $this->privateKey),
        ];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
        ])->post($this->baseUrl . '/transaction/create', $payload);

        return $response->json();
    }

    public function getTransactionDetail($reference)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
        ])->get($this->baseUrl . '/transaction/detail', [
            'reference' => $reference,
        ]);

        return $response->json();
    }
}
