<?php

namespace App\Http\Controllers;

use App\Services\VipResellerService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class VipResellerController extends Controller
{
    protected VipResellerService $vipResellerService;

    public function __construct(VipResellerService $vipResellerService)
    {
        $this->vipResellerService = $vipResellerService;
    }

    public function getGames(): JsonResponse
    {
        try {
            $games = $this->vipResellerService->getGameList();
            return response()->json([
                'success' => true,
                'data' => $games
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch games',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function order(Request $request): JsonResponse
    {
        $request->validate([
            'service' => 'required|string',
            'user_id' => 'required|string',
            'zone_id' => 'required|string',
            'target' => 'required|string',
        ]);

        try {
            $result = $this->vipResellerService->order(
                $request->service,
                $request->user_id,
                $request->zone_id,
                $request->target
            );

            return response()->json([
                'success' => true,
                'data' => $result
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to place order',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function status($id): JsonResponse
    {
        try {
            $status = $this->vipResellerService->checkStatus($id);
            return response()->json([
                'success' => true,
                'data' => $status
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to check status',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
