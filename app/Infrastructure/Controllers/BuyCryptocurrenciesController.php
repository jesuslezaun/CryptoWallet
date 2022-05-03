<?php

namespace App\Infrastructure\Controllers;

use App\Application\BuyCryptocurrencies\BuyCryptocurrenciesService;
use App\Http\Requests\CryptoMarketRequest;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BuyCryptocurrenciesController
{
    private BuyCryptocurrenciesService $buyCryptosService;
    private RequestValidation $requestValidation;

    /**
     * CoinStatusController constructor.
     */
    public function __construct(BuyCryptocurrenciesService $buyCryptosService, RequestValidation $requestValidation)
    {
        $this->buyCryptosService = $buyCryptosService;
        $this->requestValidation = $requestValidation;
    }

    public function __invoke(Request $request): JsonResponse
    {
        $error = $this->requestValidation->validate($request);
        if ($error != "") {
            return response()->json([
                'error' => $error
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
