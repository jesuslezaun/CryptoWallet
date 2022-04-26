<?php

namespace Tests\App\Infrastructure\Controller;

use App\Application\CryptoDataSource\CryptoDataSource;
use App\Domain\Coin;
use App\Domain\CoinStatus;
use Exception;
use Illuminate\Http\Response;
use Mockery;
use Tests\TestCase;

class CoinStatusControllerTest extends TestCase
{
    private CryptoDataSource $cryptoDataSource;

    /**
     * @setUp
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->cryptoDataSource = Mockery::mock(CryptoDataSource::class);
        $this->app->bind(CryptoDataSource::class, fn () => $this->cryptoDataSource);
    }

    /**
     * @test
     */
    public function coinNotFoundForGivenId()
    {
        $this->cryptoDataSource
            ->expects('findCoinStatusById')
            ->with('999')
            ->once()
            ->andThrow(new Exception('A coin with the specified id was not found'));

        $response = $this->get('/api/coin/status/999');

        $response
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertExactJson(['error' => 'A coin with the specified id was not found']);
    }

    /**
     * @test
     */
    public function serviceIsUnavailable()
    {
        $this->cryptoDataSource
            ->expects('findCoinStatusById')
            ->with('2')
            ->once()
            ->andThrow(new Exception('Service unavailable'));

        $response = $this->get('/api/coin/status/2');

        $response
            ->assertStatus(Response::HTTP_SERVICE_UNAVAILABLE)
            ->assertExactJson(['error' => 'Service unavailable']);
    }

    /**
     * @test
     */
    public function getCoinStatusForGivenId()
    {
        $coinStatus = new CoinStatus("90", "BTC", "Bitcoin", "bitcoin", 1, "601");

        $this->cryptoDataSource
            ->expects('findCoinStatusById')
            ->with('90')
            ->once()
            ->andReturn($coinStatus);

        $response = $this->get('/api/coin/status/90');

        $response
            ->assertStatus(Response::HTTP_OK)
            ->assertExactJson(['coin_id' => "90", 'symbol' => 'BTC', 'name' => 'Bitcoin',
                'name_id' => 'bitcoin', 'rank' => 1, 'price_usd' => '601']);
    }
}
