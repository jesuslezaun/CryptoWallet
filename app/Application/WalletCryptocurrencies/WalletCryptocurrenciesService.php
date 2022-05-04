<?php

namespace App\Application\WalletCryptocurrencies;

use App\Application\CryptoDataSource\CryptoDataSource;
use App\Application\CryptoDataStorage\CryptoDataStorage;
use App\Domain\Wallet;
use Exception;

class WalletCryptocurrenciesService
{
    /**
     * @var CryptoDataStorage
     */
    private CryptoDataStorage $cryptoDataStorage;
    private CryptoDataSource $cryptoDataSource;

    /**
     * WalletCryptocurrenciesService constructor.
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
     * @return array
     * @throws Exception
     */
    public function getWalletCryptocurrencies(string $wallet_id): array
    {
        $userWallet = $this->cryptoDataStorage->getWalletById($wallet_id);
        $walletCryptos = $userWallet->getCoins();
        for ($i = 0; $i < sizeof($walletCryptos); $i++) {
            $walletCryptos[$i]
                ->setValueUsd($this->cryptoDataSource->getCoinUsdValueById($walletCryptos[$i]->getCoinId()));
        }
        return $walletCryptos;
    }
}
