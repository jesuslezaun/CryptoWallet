<?php

namespace Tests\App\Application\SellCryptocurrencies;

use App\Application\CryptoDataSource\CryptoDataSource;
use App\Application\CryptoDataStorage\CryptoDataStorage;
use App\Application\SellCryptocurrencies\SellCryptocurrenciesService;
use App\Domain\Coin;
use App\Domain\Wallet;
use Mockery;
use Tests\TestCase;
use Exception;

class SellCryptocurrenciesServiceTest extends TestCase
{
    private SellCryptocurrenciesService $sellCryptosService;
    private CryptoDataSource $cryptoDataSource;
    private CryptoDataStorage $cryptoDataStorage;

    /**
     * @setUp
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->cryptoDataSource = Mockery::mock(CryptoDataSource::class);

        $this->cryptoDataStorage = Mockery::mock(CryptoDataStorage::class);

        $this->sellCryptosService = new SellCryptocurrenciesService($this->cryptoDataSource, $this->cryptoDataStorage);
    }

    /**
     * @test
     */
    public function serviceIsUnavailable()
    {
        $coinId = "1";
        $walletId = "5";
        $amountUsd = 0;

        $this->cryptoDataSource
            ->expects('findCoinById')
            ->with($coinId)
            ->once()
            ->andThrow(new Exception('Service unavailable'));

        $this->expectException(Exception::class);

        $this->sellCryptosService->execute($coinId, $walletId, $amountUsd);
    }

    /**
     * @test
     */
    public function coinNotFoundForGivenId()
    {
        $coinId = "999";
        $walletId = "5";
        $amountUsd = 0;

        $this->cryptoDataSource
            ->expects('findCoinById')
            ->with($coinId)
            ->once()
            ->andThrow(new Exception('A coin with the specified id was not found'));

        $this->expectException(Exception::class);

        $this->sellCryptosService->execute($coinId, $walletId, $amountUsd);
    }

    /**
     * @test
     */
    public function walletNotFoundForGivenId()
    {
        $coinId = "999";
        $walletId = "99";
        $amountUsd = 0;

        $this->cryptoDataSource
            ->expects('findCoinById')
            ->with($coinId)
            ->once();

        $this->cryptoDataStorage
            ->expects('getWalletById')
            ->with($walletId)
            ->once()
            ->andThrow(new Exception('A wallet with the specified id was not found'));

        $this->expectException(Exception::class);

        $this->sellCryptosService->execute($coinId, $walletId, $amountUsd);
    }

    /**
     * @test
     */
    public function amountNotPositive()
    {
        $coinId = "1";
        $walletId = "1";
        $amountUsd = 0;

        $this->cryptoDataSource
            ->expects('findCoinById')
            ->with($coinId)
            ->once();
        $this->cryptoDataStorage
            ->expects('getWalletById')
            ->with($walletId)
            ->once();

        $this->expectException(Exception::class);

        $this->sellCryptosService->execute($coinId, $walletId, $amountUsd);
    }

    /**
     * @test
     */
    public function coinNotInWallet()
    {
        $coinId = "1";
        $walletId = "1";
        $amountUsd = 1;

        $this->cryptoDataSource
            ->expects('findCoinById')
            ->with($coinId)
            ->once();
        $this->cryptoDataStorage
            ->expects('getWalletById')
            ->with($walletId)
            ->once();

        $this->expectException(Exception::class);

        $this->sellCryptosService->execute($coinId, $walletId, $amountUsd);
    }

    /**
     * @test
     */
    public function notEnoughCoinsInWallet()
    {
        $coinId = "90";
        $coin = new Coin($coinId, "Bitcoin", "BTC", 2, 6010);
        $walletId = "1";
        $wallet = new Wallet($walletId);
        $wallet->insertCoin($coin);
        $amountUsd = 12021;

        $this->cryptoDataSource
            ->expects('findCoinById')
            ->with($coinId)
            ->once();
        $this->cryptoDataStorage
            ->expects('getWalletById')
            ->with($walletId)
            ->once();

        $this->expectException(Exception::class);

        $this->sellCryptosService->execute($coinId, $walletId, $amountUsd);
    }

    /**
     * @test
     */
    public function coinSoldCompletely()
    {
        $coinId = "90";
        $coin = new Coin($coinId, "Bitcoin", "BTC", 1, 6010);
        $walletId = "1";
        $wallet = new Wallet($walletId);
        $wallet->insertCoin($coin);
        $amountUsd = 6010;
        $expectedWallet = new Wallet($walletId);

        $this->cryptoDataSource
            ->expects('findCoinById')
            ->with($coinId)
            ->once()
            ->andReturn($coin);
        $this->cryptoDataStorage
            ->expects('getWalletById')
            ->with($walletId)
            ->once()
            ->andReturn($wallet);
        $this->cryptoDataStorage
            ->expects('updateWallet')
            ->with(\Mockery::on(function ($walletParameter) use ($expectedWallet) {
                $coinsParameter = $walletParameter->getCoins();
                $coinsExpected = $expectedWallet->getCoins();

                return (sizeof($coinsParameter) == sizeof($coinsExpected));
            }))
            ->once();

        $sellCryptoResponse = $this->sellCryptosService->execute($coinId, $walletId, $amountUsd);

        $this->assertEmpty($sellCryptoResponse);
    }

    /**
     * @test
     */
    public function sellCoin()
    {
        $coinId = "90";
        $coin = new Coin($coinId, "Bitcoin", "BTC", 2, 6010);
        $walletId = "1";
        $wallet = new Wallet($walletId);
        $wallet->insertCoin($coin);
        $amountUsd = 6010;
        $expectedWallet = new Wallet($walletId);
        $coinExpected = new Coin($coinId, "Bitcoin", "BTC", 1, 6010);
        $expectedWallet->insertCoin($coinExpected);

        $this->cryptoDataSource
            ->expects('findCoinById')
            ->with($coinId)
            ->once()
            ->andReturn($coin);
        $this->cryptoDataStorage
            ->expects('getWalletById')
            ->with($walletId)
            ->once()
            ->andReturn($wallet);
        $this->cryptoDataStorage
            ->expects('updateWallet')
            ->with(\Mockery::on(function ($walletParameter) use ($expectedWallet) {
                $coinsParameter = $walletParameter->getCoins();
                $coinSell = $coinsParameter[$walletParameter->isCoinInWallet("90")];
                $coinsExpected = $expectedWallet->getCoins();
                $expectedCoinSell = $coinsExpected[$expectedWallet->isCoinInWallet("90")];

                return ($coinSell->getAmount() == $expectedCoinSell->getAmount());
            }))
            ->once();

        $sellCryptoResponse = $this->sellCryptosService->execute($coinId, $walletId, $amountUsd);

        $this->assertEmpty($sellCryptoResponse);
    }
}
