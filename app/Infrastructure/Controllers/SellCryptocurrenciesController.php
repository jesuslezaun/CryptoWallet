<?php

namespace App\Infrastructure\Controllers;

use App\Application\SellCryptocurrencies\SellCryptocurrenciesService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Exception;

class SellCryptocurrenciesController
{
    private SellCryptocurrenciesService $sellCryptosService;
    private RequestValidation $requestValidation;

    /**
     * CoinStatusController constructor.
     */
    public function __construct(SellCryptocurrenciesService $sellCryptosService, RequestValidation $requestValidation)
    {
        $this->sellCryptosService = $sellCryptosService;
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
            $this->sellCryptosService
                ->execute($request->input('coin_id'), $request->input('wallet_id'), $request->input('amount_usd'));
        } catch (Exception $exception) {
            if ($exception->getMessage() == "Service unavailable") {
                return response()->json([
                    'error' => $exception->getMessage()
                ], Response::HTTP_SERVICE_UNAVAILABLE);
            }

            if (
                $exception->getMessage() == "Amount should be positive" ||
                $exception->getMessage() == "Coin is not in wallet"  ||
                $exception->getMessage() == "No enough coins to sell"
            ) {
                return response()->json([
                    'error' => $exception->getMessage()
                ], Response::HTTP_BAD_REQUEST);
            }

            return response()->json([
                'error' => $exception->getMessage()
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([], Response::HTTP_OK);
    }
}
