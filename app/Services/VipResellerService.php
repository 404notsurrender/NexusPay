<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class VipResellerService
{
    protected $key;
    protected $uid;
    protected $baseUrl;

    public function __construct()
    {
        $this->key = config('services.vipreseller.key');
        $this->uid = config('services.vipreseller.uid');
        $this->baseUrl = 'https://vip-reseller.co.id/api/game-feature';
    }

    protected function post($endpoint, $payload = [])
    {
        $response = Http::asForm()->post($this->baseUrl, $payload);
        return $response->json();
    }

    public function getGameList()
    {
        $payload = [
            'key' => $this->key,
            'sign' => md5($this->key . $this->uid),
            'type' => 'services',
        ];

        return $this->post('', $payload);
    }

    public function checkStatus($orderId)
    {
        $payload = [
            'key' => $this->key,
            'sign' => md5($this->key . $this->uid),
            'type' => 'status',
            'trxid' => $orderId,
        ];

        return $this->post('', $payload);
    }

    public function order($serviceId, $userId, $zoneId, $target)
    {
        $payload = [
            'key' => $this->key,
            'sign' => md5($this->key . $this->uid),
            'type' => 'order',
            'service' => $serviceId,
            'data_no' => $userId,
            'data_zone' => $zoneId,
            'data_target' => $target,
        ];

        return $this->post('', $payload);
    }
}
