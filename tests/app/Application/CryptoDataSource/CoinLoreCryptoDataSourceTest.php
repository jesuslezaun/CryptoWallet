<?php

namespace Tests\App\Application\CryptoDataSource;

use App\Application\CryptoDataSource\CoinLoreCryptoDataSource;
use App\Application\CryptoDataSource\CryptoDataSource;
use App\Domain\Coin;
use App\Domain\CoinStatus;
use PHPUnit\Framework\TestCase;

class CoinLoreCryptoDataSourceTest extends TestCase
{
    private CryptoDataSource $clCryptoDataSource;

    /**
     * @setUp
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->clCryptoDataSource = new CoinLoreCryptoDataSource();
    }

    /**
     * @test
     */
    public function findCoinStatusById()
    {
        $btcFakeStatus = new CoinStatus("90", "BTC", "Bitcoin", "bitcoin", 1, "33500");
        $btcRealStatus = $this->clCryptoDataSource->findCoinStatusById("90");

        $this->assertEquals($btcFakeStatus->getCoinId(), $btcRealStatus->getCoinId());
        $this->assertEquals($btcFakeStatus->getSymbol(), $btcRealStatus->getSymbol());
        $this->assertEquals($btcFakeStatus->getName(), $btcRealStatus->getName());
        $this->assertEquals($btcFakeStatus->getNameId(), $btcRealStatus->getNameId());
        $this->assertEquals($btcFakeStatus->getRank(), $btcRealStatus->getRank());
    }

    /**
     * @test
     */
    public function findCoinById()
    {
        $btcFakeCoin = new Coin("90", "Bitcoin", "BTC", 0, "33500");
        $btcRealCoin = $this->clCryptoDataSource->findCoinById("90");

        $this->assertEquals($btcFakeCoin->getCoinId(), $btcRealCoin->getCoinId());
        $this->assertEquals($btcFakeCoin->getSymbol(), $btcRealCoin->getSymbol());
        $this->assertEquals($btcFakeCoin->getName(), $btcRealCoin->getName());
        $this->assertEquals($btcFakeCoin->getAmount(), $btcRealCoin->getAmount());
    }

    /**
     * @test
     */
    public function getCoinUsdValueById()
    {
        $btcPrice = $this->clCryptoDataSource->getCoinUsdValueById("90");

        $this->assertIsFloat($btcPrice);
    }
}
