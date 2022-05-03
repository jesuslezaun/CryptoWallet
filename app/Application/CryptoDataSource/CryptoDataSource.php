<?php

namespace App\Application\CryptoDataSource;

use App\Domain\CoinStatus;

interface CryptoDataSource
{
    public function findCoinStatusById(string $coinId): CoinStatus;
    public function getCoinUsdValueById(string $coindId): float;
}
