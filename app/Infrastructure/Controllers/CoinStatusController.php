<?php

namespace App\Infrastructure\Controllers;

use App\Application\CoinStatus\CoinStatusService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;

class CoinStatusController
{
    private CoinStatusService $coinStatusService;

    /**
     * CoinStatusController constructor.
     */
    public function __construct(CoinStatusService $coinStatusService)
    {
        $this->coinStatusService = $coinStatusService;
    }

    public function __invoke(int $coinId): JsonResponse
    {
        try {
            $coinStatus = $this->coinStatusService->getCoinStatus($coinId);
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

        return response()->json([
            'coin_id' => $coinStatus->getCoinId(),
            'symbol' => $coinStatus->getSymbol(),
            'name' => $coinStatus->getName(),
            'name_id' => $coinStatus->getNameId(),
            'rank' => $coinStatus->getRank(),
            'price_usd' => $coinStatus->getPriceUsd(),
        ], Response::HTTP_OK);
    }
}
