<?php

namespace App\Application\BuyCryptocurrencies;

use App\Application\CryptoDataSource\CryptoDataSource;
use App\Application\CryptoDataStorage\CryptoDataStorage;
use Exception;

class BuyCryptocurrenciesService
{
    private CryptoDataSource $cryptoDataSource;
    private CryptoDataStorage $cryptoDataStorage;

    /**
     * CoinStatusService constructor.
     * @param CryptoDataSource $cryptoDataSource
     * @param CryptoDataStorage $cryptoDataStorage
     */
    public function __construct(CryptoDataSource $cryptoDataSource, CryptoDataStorage $cryptoDataStorage)
    {
        $this->cryptoDataSource = $cryptoDataSource;
        $this->cryptoDataStorage = $cryptoDataStorage;
    }

    /**
     * @throws Exception
     */
    public function execute(string $coinId, string $walletId, float $amountUsd): void
    {
        $coin = $this->cryptoDataSource->findCoinById($coinId);

        $wallet = $this->cryptoDataStorage->getWalletById($walletId);

        if ($amountUsd <= 0) {
            throw new Exception("Amount should be positive");
        }
    }
}
