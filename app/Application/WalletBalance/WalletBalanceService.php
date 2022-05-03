<?php

namespace App\Application\WalletBalance;

use App\Application\CryptoDataSource\CryptoDataSource;
use App\Application\CryptoDataStorage\CryptoDataStorage;
use Exception;

class WalletBalanceService
{
    /**
     * @var CryptoDataStorage
     */
    private CryptoDataStorage $cryptoDataStorage;
    private CryptoDataSource $cryptoDataSource;

    /**
     * WalletBalanceService constructor.
     * @param CryptoDataStorage $cryptoDataStorage
     * @param CryptoDataSource $cryptoDataSource
     */
    public function __construct(CryptoDataStorage $cryptoDataStorage, CryptoDataSource $cryptoDataSource)
    {
        $this->cryptoDataStorage = $cryptoDataStorage;
        $this->cryptoDataSource = $cryptoDataSource;
    }

    /**
     * @param string $wallet_id
     * @return float
     */
    public function getWalletBalance(string $wallet_id): float
    {
        $userWallet = $this->cryptoDataStorage->getWalletById($wallet_id);
        return $userWallet->getBalance($this->cryptoDataSource);
    }
}
