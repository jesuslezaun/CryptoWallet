<?php

namespace App\Application\CryptoDataSource;

use App\Domain\CoinStatus;
use App\Domain\Wallet;

interface CryptoDataSource
{
    public function findCoinStatusById(string $coinId): CoinStatus;
    public function findWalletCryptocurrenciesById(string $wallet_id): array;
}
