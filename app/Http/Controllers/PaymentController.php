<?php
namespace App\Services;
use App\Models\PaymentMethod;
use App\Models\Order;
use Illuminate\Support\Facades\Http;
class TripayService
{
    protected $apiKey;
    protected $privateKey;
    protected $merchantCode;
    protected $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.tripay.api_key');
        $this->privateKey = config('services.tripay.private_key');
        $this->merchantCode = config('services.tripay.merchant_code');
        $this->baseUrl = config('services.tripay.base_url');
    }

    public function createTransaction(Order $order, PaymentMethod $paymentMethod)
    {
        $payload = [
            'merchant_ref' => 'ORDER-' . $order->id,
            'amount' => $order->total_price,
            'payment_method' => $paymentMethod->name,
            'customer_name' => optional($order->user)->name ?? 'Guest',
            'customer_email' => optional($order->user)->email ?? 'guest@example.com',
            'order_items' => [
                [
                    'name' => $order->product->name,
                    'price' => $order->total_price,
                    'quantity' => $order->quantity,
                ]
            ],
            'callback_url' => route('tripay.callback'),
            'return_url' => route('tripay.return'),
        ];
        $signature = hash_hmac('sha256', $this->merchantCode . $payload['merchant_ref'] . $payload['amount'], $this->privateKey);
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post($this->baseUrl . '/transactions/create', array_merge($payload, ['signature' => $signature]));
        if ($response->failed()) {
            throw new \Exception('Failed to create Tripay transaction: ' . $response->body());
        }

        // return parsed response data
        return $response->json();
    }
}