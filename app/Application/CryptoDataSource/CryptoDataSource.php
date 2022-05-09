<?php

namespace App\Application\CryptoDataSource;

use App\Domain\Coin;
use App\Domain\CoinStatus;

interface CryptoDataSource
{
    public function findCoinStatusById(string $coinId): CoinStatus;

    public function findCoinById(string $coinId): Coin;
}
