<?php

namespace App\Application\CryptoDataStorage;

use App\Domain\Wallet;
use Exception;
use Illuminate\Support\Facades\Cache;

class CacheCryptoDataStorage implements CryptoDataStorage
{
    public function getWalletById(string $wallet_id): Wallet
    {
    }

    public function updateWallet(Wallet $wallet): void
    {
        // TODO: Implement updateWallet() method.
    }

    public function createWallet(): Wallet
    {
        $walletId = 1;
        while (Cache::has(strval($walletId))) {
            $walletId += 1;
        }

        $wallet = new Wallet(strval($walletId));
        Cache::put(strval($walletId), $wallet);

        return $wallet;
    }
}
