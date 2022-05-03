<?php

namespace App\Domain;

use App\Application\CryptoDataSource\CryptoDataSource;

class Wallet
{
    private string $wallet_id;
    private array $coins;

    /**
     * @param String $wallet_id
     */
    public function __construct(string $wallet_id)
    {
        $this->wallet_id = $wallet_id;
        $this->coins = [];
    }

    /**
     * @return string
     */
    public function getWalletId(): string
    {
        return $this->wallet_id;
    }

    /**
     * @return array
     */
    public function getCoins(): array
    {
        return $this->coins;
    }

    public function insertCoin(Coin $coin): void
    {
        $this->coins[] = $coin;
    }

    public function getBalance(CryptoDataSource $cryptoDataSource): float
    {
        $balance = 0;
        foreach ($this->coins as $coin) {
            $balance += $coin->getAmount() * $cryptoDataSource->getCoinUsdValueById($coin->getCoinId());
        }
        return $balance;
    }
}
