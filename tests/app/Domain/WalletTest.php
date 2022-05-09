<?php

namespace Tests\app\Domain;

use App\Domain\Coin;
use App\Domain\Wallet;
use PHPUnit\Framework\TestCase;

class WalletTest extends TestCase
{
    /**
     * @test
     */
    public function newWalletIsEmpty()
    {
        $wallet = new Wallet("1");

        $this->assertEquals([], $wallet->getCoins());
    }

    /**
     * @test
     */
    public function walletHasCoins()
    {
        $wallet = new Wallet("1");
        $coin = new Coin("90", "Bitcoin", "BTC", 0, 6010);
        $wallet->insertCoin($coin);

        $this->assertEquals([$coin], $wallet->getCoins());
    }

    /**
     * @test
     */
    public function setCoinsInWallet()
    {
        $wallet = new Wallet("1");
        $coin1 = new Coin("90", "Bitcoin", "BTC", 0, 6010);
        $coin2 = new Coin("80", "Ethereum", "ETH", 10, 1000);
        $coins = [$coin1, $coin2];
        $wallet->setCoins($coins);

        $this->assertEquals($coins, $wallet->getCoins());
    }

    /**
     * @test
     */
    public function coinIsNotInWallet()
    {
        $wallet = new Wallet("1");

        $indexResponse = $wallet->isCoinInWallet("90");

        $this->assertEquals(-1, $indexResponse);
    }

    /**
     * @test
     */
    public function coinIsInWallet()
    {
        $wallet = new Wallet("1");
        $coin1 = new Coin("90", "Bitcoin", "BTC", 0, 6010);
        $wallet->insertCoin($coin1);
        $coin2 = new Coin("80", "Ethereum", "ETH", 10, 1000);
        $wallet->insertCoin($coin2);

        $indexResponse = $wallet->isCoinInWallet("80");

        $this->assertEquals(1, $indexResponse);
    }
}
