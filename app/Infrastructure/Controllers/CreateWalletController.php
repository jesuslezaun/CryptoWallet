<?php

namespace App\Infrastructure\Controllers;

use App\Application\CreateWallet\CreateWalletService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Exception;

class CreateWalletController
{
    private CreateWalletService $createWalletService;

    /**
     * CoinStatusController constructor.
     */
    public function __construct(CreateWalletService $createWalletService)
    {
        $this->createWalletService = $createWalletService;
    }

    public function __invoke(): JsonResponse
    {
        try {
            $userWalletId = $this->createWalletService->execute();
        } catch (Exception $exception) {
            return response()->json([
                'error' => $exception->getMessage()
            ], Response::HTTP_SERVICE_UNAVAILABLE);
        }

        return response()->json([
            'wallet_id' => $userWalletId,
        ], Response::HTTP_OK);
    }
}
