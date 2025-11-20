<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Order;
use App\Services\DigiFlazzService;
use App\Services\VipResellerService;

class UpdateOrderStatusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function handle()
    {
        $order = $this->order;

        $service = $this->getServiceForProduct($order->product);

        if (!$service) {
            return;
        }

        $status = $service->checkStatus('ORDER-' . $order->id);

        if ($status['status'] === 'success') {
            $order->update(['status' => 'completed']);
        } elseif ($status['status'] === 'failed') {
            $order->update(['status' => 'failed']);
        }
        // Keep checking if still processing
    }

    private function getServiceForProduct($product)
    {
        if ($product->provider === 'digiflazz') {
            return app(DigiFlazzService::class);
        } elseif ($product->provider === 'vip_reseller') {
            return app(VipResellerService::class);
        }

        return null;
    }
}
