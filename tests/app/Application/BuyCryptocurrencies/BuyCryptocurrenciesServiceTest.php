<?php

namespace Tests\App\Application\BuyCryptocurrencies;

use App\Application\BuyCryptocurrencies\BuyCryptocurrenciesService;
use App\Application\CoinStatus\CoinStatusService;
use App\Application\CryptoDataSource\CryptoDataSource;
use App\Application\CryptoDataStorage\CryptoDataStorage;
use Exception;
use Mockery;
use PHPUnit\Framework\TestCase;

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
}
