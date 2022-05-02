<?php

namespace App\Application\BuyCryptocurrencies;

use App\Application\CryptoDataSource\CryptoDataSource;
use Exception;

class BuyCryptocurrenciesService
{
    /**
     * @var CryptoDataSource
     */
    private CryptoDataSource $cryptoDataSource;

    /**
     * CoinStatusService constructor.
     * @param CryptoDataSource $cryptoDataSource
     */
    public function __construct(CryptoDataSource $cryptoDataSource)
    {
        $this->cryptoDataSource = $cryptoDataSource;
    }

    /**
     * @throws Exception
     */
    public function execute(string $coinId, string $walletId, float $amountUsd): void
    {
        $coin = $this->cryptoDataSource->findCoinById($coinId);
    }
}
