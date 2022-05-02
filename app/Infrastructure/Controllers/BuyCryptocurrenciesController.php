<?php

namespace App\Infrastructure\Controllers;

use App\Application\BuyCryptocurrencies\BuyCryptocurrenciesService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BuyCryptocurrenciesController
{
    private BuyCryptocurrenciesService $buyCryptosService;

    /**
     * CoinStatusController constructor.
     */
    public function __construct(BuyCryptocurrenciesService $buyCryptosService)
    {
        $this->buyCryptosService = $buyCryptosService;
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
        if (!($request->has('amount_usd'))) {
            return response()->json([
                'error' => 'Amount missing from request'
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            $this->buyCryptosService
                ->execute($request->input('coin_id'), $request->input('wallet_id'), $request->input('amount_usd'));
        } catch (Exception $exception) {
            if ($exception->getMessage() == "Service unavailable") {
                return response()->json([
                    'error' => $exception->getMessage()
                ], Response::HTTP_SERVICE_UNAVAILABLE);
            }
            return response()->json([
                'error' => $exception->getMessage()
            ], Response::HTTP_NOT_FOUND);
        }
    }
}
