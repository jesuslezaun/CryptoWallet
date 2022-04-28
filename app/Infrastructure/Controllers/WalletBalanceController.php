<?php

namespace App\Infrastructure\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use App\Application\WalletBalance\WalletBalanceService;

class WalletBalanceController
{
    private WalletBalanceService $walletBalanceService;

    /**
     * WalletBalanceController constructor.
     */
    public function __construct(WalletBalanceService $walletBalanceService)
    {
        $this->walletBalanceService = $walletBalanceService;
    }

    public function __invoke(string $wallet_id): JsonResponse
    {
        try {
            $walletBalance = $this->walletBalanceService->getWalletBalance($wallet_id);
        } catch (Exception $exception) {
            return response()->json([
                'error' => $exception->getMessage()
            ], Response::HTTP_NOT_FOUND);
        }
    }
}
