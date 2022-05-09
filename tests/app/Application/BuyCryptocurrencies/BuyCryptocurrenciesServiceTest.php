<?php

namespace Tests\App\Application\BuyCryptocurrencies;

use App\Application\BuyCryptocurrencies\BuyCryptocurrenciesService;
use App\Application\CoinStatus\CoinStatusService;
use App\Application\CryptoDataSource\CryptoDataSource;
use App\Application\CryptoDataStorage\CryptoDataStorage;
use App\Domain\Coin;
use App\Domain\Wallet;
use Exception;
use Mockery;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\Debug\WrappedListener;

class BuyCryptocurrenciesServiceTest extends TestCase
{
    private BuyCryptocurrenciesService $buyCryptosService;
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

        $this->buyCryptosService = new BuyCryptocurrenciesService($this->cryptoDataSource, $this->cryptoDataStorage);
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

        $this->buyCryptosService->execute($coinId, $walletId, $amountUsd);
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

        $this->buyCryptosService->execute($coinId, $walletId, $amountUsd);
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

        $this->buyCryptosService->execute($coinId, $walletId, $amountUsd);
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

        $this->buyCryptosService->execute($coinId, $walletId, $amountUsd);
    }

    /**
     * @test
     */
    public function newCoinBought()
    {
        $coinId = "90";
        $coin = new Coin($coinId, "Bitcoin", "BTC", 0, 6010);
        $walletId = "1";
        $wallet = new Wallet($walletId);
        $amountUsd = 6010;
        $expectedWallet = new Wallet($walletId);
        $coin->setAmount(1);
        $expectedWallet->insertCoin($coin);

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
                $coinBought = $coinsParameter[$walletParameter->isCoinInWallet("90")];
                $coinsExpected = $expectedWallet->getCoins();
                $expectedCoinBought = $coinsExpected[$expectedWallet->isCoinInWallet("90")];

                return ($coinBought->getAmount() == $expectedCoinBought->getAmount());
            }))
            ->once();

        $buyCryptoResponse = $this->buyCryptosService->execute($coinId, $walletId, $amountUsd);

        $this->assertEmpty($buyCryptoResponse);
    }

    /**
     * @test
     */
    public function existingCoinBought()
    {
        $coinId = "90";
        $coin = new Coin($coinId, "Bitcoin", "BTC", 10, 6010);
        $walletId = "1";
        $wallet = new Wallet($walletId);
        $wallet->insertCoin($coin);
        $amountUsd = 6010;
        $expectedWallet = new Wallet($walletId);
        $coin->setAmount(11);
        $expectedWallet->insertCoin($coin);

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
                $coinBought = $coinsParameter[$walletParameter->isCoinInWallet("90")];
                $coinsExpected = $expectedWallet->getCoins();
                $expectedCoinBought = $coinsExpected[$expectedWallet->isCoinInWallet("90")];

                return ($coinBought->getAmount() == $expectedCoinBought->getAmount());
            }))
            ->once();

        $buyCryptoResponse = $this->buyCryptosService->execute($coinId, $walletId, $amountUsd);

        $this->assertEmpty($buyCryptoResponse);
    }
}
