<?php

namespace App\Application\CryptoDataSource;

use App\Domain\CoinStatus;

interface CryptoDataSource
{
    public function findCoinById(int $coinId): CoinStatus;
}
