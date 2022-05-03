<?php

namespace App\Application\CreateWallet;

use App\Application\CryptoDataStorage\CryptoDataStorage;
use Exception;

class CreateWalletService
{
    /**
     * @var CryptoDataStorage
     */
    private CryptoDataStorage $cryptoDataStorage;

    /**
     * CoinStatusService constructor.
     * @param CryptoDataStorage $cryptoDataStorage
     */
    public function __construct(CryptoDataStorage $cryptoDataStorage)
    {
        $this->cryptoDataStorage = $cryptoDataStorage;
    }

    /**
     * @return string
     * @throws Exception
     */
    public function createWallet(): string
    {
        $user_wallet = $this->cryptoDataStorage->createWallet();
        return $user_wallet->getWalletId();
    }
}
