<?php

namespace App\Domain;

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

    public function setCoins(array $walletCoins)
    {
        $this->coins = $walletCoins;
    }

    public function insertCoin(Coin $coin): void
    {
        $this->coins[] = $coin;
    }

    public function isCoinInWallet(string $coin_id): int
    {
        for ($index = 0; $index < sizeof($this->coins); $index++) {
            if ($this->coins[$index]->getCoinId() == $coin_id) {
                return $index;
            }
        }

        return -1;
    }
}
