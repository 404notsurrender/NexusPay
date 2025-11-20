<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Services\TripayService;

class PaymentCallbackController extends Controller
{
    protected $tripayService;

    public function __construct(TripayService $tripayService)
    {
        $this->tripayService = $tripayService;
    }

    public function handleCallback(Request $request)
    {
        $callbackSignature = $request->header('X-Callback-Signature');
        $json = $request->getContent();
        $signature = hash_hmac('sha256', $json, env('TRIPAY_PRIVATE_KEY'));

        if ($callbackSignature !== $signature) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $data = json_decode($json, true);

        $order = Order::where('payment_reference', $data['reference'])->first();

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        if ($data['status'] === 'PAID') {
            $order->update(['status' => 'processing']);
            // Dispatch job to process order with DigiFlazz/VIP
            // ProcessOrderJob::dispatch($order);
        }

        return response()->json(['message' => 'Callback processed']);
    }
}
