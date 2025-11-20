<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class FonnteService
{
    protected $token;
    protected $baseUrl;

    public function __construct()
    {
        $this->token = config('services.fonnte.token');
        $this->baseUrl = 'https://api.fonnte.com';
    }

    public function sendMessage($target, $message)
    {
        $response = Http::withHeaders([
            'Authorization' => $this->token,
        ])->post($this->baseUrl . '/send', [
            'target' => $target,
            'message' => $message,
        ]);

        return $response->json();
    }

    public function sendOrderNotification($order)
    {
        $message = "Pesanan baru #" . $order->id . "\n" .
                   "Produk: " . $order->product->name . "\n" .
                   "Jumlah: " . $order->quantity . "\n" .
                   "Total: Rp " . number_format($order->total_price) . "\n" .
                   "Status: " . $order->status;

        return $this->sendMessage($order->user->phone ?? config('services.fonnte.admin_phone'), $message);
    }
}
