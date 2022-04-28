<?php

namespace App\Application\WalletBalance;

use App\Application\CryptoDataStorage\CryptoDataStorage;
use Exception;

class WalletBalanceService
{
    /**
     * @var CryptoDataStorage
     */
    private CryptoDataStorage $cryptoDataStorage;

    /**
     * WalletBalanceService constructor.
     * @param CryptoDataStorage $cryptoDataStorage
     */
    public function __construct(CryptoDataStorage $cryptoDataStorage)
    {
        $this->cryptoDataStorage = $cryptoDataStorage;
    }

    /**
     * @param string $wallet_id
     * @return float
     * @throws Exception
     */
    public function getWalletBalance(string $wallet_id): float
    {
        $userWallet = $this->cryptoDataStorage->getWalletById($wallet_id);
        return $userWallet->getBalance();
    }
}
