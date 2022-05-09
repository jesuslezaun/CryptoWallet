<?php

namespace App\Application\CryptoDataStorage;

use App\Domain\Wallet;

interface CryptoDataStorage
{
    public function getWalletById(string $wallet_id): Wallet;

    public function updateWallet(Wallet $wallet): void;
}
