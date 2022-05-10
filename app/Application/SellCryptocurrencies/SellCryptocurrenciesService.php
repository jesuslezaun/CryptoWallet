<?php

namespace App\Application\SellCryptocurrencies;

use App\Application\CryptoDataSource\CryptoDataSource;
use App\Application\CryptoDataStorage\CryptoDataStorage;
use Mockery\Exception;

class SellCryptocurrenciesService
{
    private CryptoDataSource $cryptoDataSource;
    private CryptoDataStorage $cryptoDataStorage;

    /**
     * CoinStatusService constructor.
     * @param CryptoDataSource $cryptoDataSource
     * @param CryptoDataStorage $cryptoDataStorage
     */
    public function __construct(CryptoDataSource $cryptoDataSource, CryptoDataStorage $cryptoDataStorage)
    {
        $this->cryptoDataSource = $cryptoDataSource;
        $this->cryptoDataStorage = $cryptoDataStorage;
    }

    /**
     * @throws Exception
     */
    public function execute(string $coinId, string $walletId, float $amountUsd): void
    {
        $coin = $this->cryptoDataSource->findCoinById($coinId);

        $wallet = $this->cryptoDataStorage->getWalletById($walletId);

        if ($amountUsd <= 0) {
            throw new Exception("Amount should be positive");
        }

        $index = $wallet->isCoinInWallet($coinId);

        if ($index == -1) {
            throw new Exception("Coin is not in wallet");
        } else {
            $walletCoins = $wallet->getCoins();
            $amountCoin = $amountUsd / $coin->getValueUsd();
            if ($walletCoins[$index]->getAmount() < $amountCoin) {
                throw new Exception('No enough coins to sell');
            }
            $walletCoins[$index]
                ->setAmount($walletCoins[$index]->getAmount() - ($amountUsd / $coin->getValueUsd()));
            $wallet->setCoins($walletCoins);
        }

        $this->cryptoDataStorage->updateWallet($wallet);
    }
}
