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
        $walletCryptos = $userWallet->getCoins();
        $balance = 0;
        for ($i = 0; $i < sizeof($walletCryptos); $i++) {
            //$walletCryptos[$i]
            // ->setValueUsd($this->cryptoDataSource->getCoinUsdValueById($walletCryptos[$i]->getCoinId()));
            $balance +=
                $walletCryptos[$i]->getAmount() *
                $this->cryptoDataSource->getCoinUsdValueById($walletCryptos[$i]->getCoinId());
        }
        //$userWallet->setCoins($walletCryptos);
        //return $userWallet->getBalance();
        return $balance;
    }
}
