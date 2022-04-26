<?php

namespace App\Infrastructure\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use App\Application\WalletCryptocurrencies\WalletCryptocurrenciesService;

class WalletCryptocurrenciesController
{
    private WalletCryptocurrenciesService $walletCryptoService;

    /**
     * WalletCryptocurrenciesController constructor.
     */
    public function __construct(WalletCryptocurrenciesService $walletCryptoService)
    {
        $this->walletCryptoService = $walletCryptoService;
    }

    public function __invoke(string $wallet_id): JsonResponse
    {
        try {
            $walletCryptos = $this->walletCryptoService->getWalletCryptocurrencies($wallet_id);
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
