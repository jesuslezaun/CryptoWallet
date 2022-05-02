<?php

namespace App\Infrastructure\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BuyCryptocurrenciesController
{
    /**
     * CoinStatusController constructor.
     */
    public function __construct()
    {
    }

    public function __invoke(Request $request): JsonResponse
    {
        if (!($request->has('coin_id'))) {
            return response()->json([
                'error' => 'Coin id missing from request'
            ], Response::HTTP_BAD_REQUEST);
        }
        if (!($request->has('wallet_id'))) {
            return response()->json([
                'error' => 'Wallet id missing from request'
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
