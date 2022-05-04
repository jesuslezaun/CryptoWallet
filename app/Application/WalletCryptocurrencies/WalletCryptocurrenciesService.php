<?php

namespace App\Application\WalletCryptocurrencies;

use App\Application\CryptoDataStorage\CryptoDataStorage;
use Exception;

class WalletCryptocurrenciesService
{
    /**
     * @var CryptoDataStorage
     */
    private CryptoDataStorage $cryptoDataStorage;

    /**
     * WalletCryptocurrenciesService constructor.
     * @param CryptoDataStorage $cryptoDataStorage
     */
    public function __construct(CryptoDataStorage $cryptoDataStorage)
    {
        $this->cryptoDataStorage = $cryptoDataStorage;
    }

    /**
     * @param string $wallet_id
     * @return array
     * @throws Exception
     */
    public function getWalletCryptocurrencies(string $wallet_id): array
    {
        $userWallet = $this->cryptoDataStorage->getWalletById($wallet_id);
        return $userWallet->getCoins();
    }
}
