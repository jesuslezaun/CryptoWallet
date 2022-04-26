<?php

namespace Tests\app\Application\CoinStatus;

use App\Application\CoinStatus\CoinStatusService;
use App\Application\CryptoDataSource\CryptoDataSource;
use App\Domain\Coin;
use App\Domain\CoinStatus;
use Exception;
use Mockery;
use PHPUnit\Framework\TestCase;

class CoinStatusServiceTest extends TestCase
{
    private CoinStatusService $coinStatusService;
    private CryptoDataSource $cryptoDataSource;

    /**
     * @setUp
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->cryptoDataSource = Mockery::mock(CryptoDataSource::class);

        $this->coinStatusService = new CoinStatusService($this->cryptoDataSource);
    }

    /**
     * @test
     */
    public function coinNotFoundForGivenId()
    {
        $coinId = "999";

        $this->cryptoDataSource
            ->expects('findCoinById')
            ->with($coinId)
            ->once()
            ->andThrow(new Exception('A coin with the specified id was not found'));

        $this->expectException(Exception::class);

        $this->coinStatusService->getCoinStatus($coinId);
    }

    /**
     * @test
     */
    public function serviceIsUnavailable()
    {
        $coinId = "1";

        $this->cryptoDataSource
            ->expects('findCoinById')
            ->with($coinId)
            ->once()
            ->andThrow(new Exception('Service unavailable'));

        $this->expectException(Exception::class);

        $this->coinStatusService->getCoinStatus($coinId);
    }

    /**
     * @test
     */
    public function getCoinStatusForGivenId()
    {
        $coinStatus = new CoinStatus("90", "BTC", "Bitcoin", "bitcoin", 1, "600");

        $this->cryptoDataSource
            ->expects('findCoinById')
            ->with("90")
            ->once()
            ->andReturn($coinStatus);

        $coinStatusService = $this->coinStatusService->getCoinStatus("90");

        $this->assertEquals($coinStatus, $coinStatusService);
    }
}
