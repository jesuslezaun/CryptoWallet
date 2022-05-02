<?php

namespace Tests\app\Infrastructure\Controller;

use App\Application\CryptoDataSource\CryptoDataSource;
use App\Infrastructure\Controllers\BuyCryptocurrenciesController;
use Exception;
use Illuminate\Http\Response;
use Mockery;
use Tests\TestCase;

class BuyCryptocurrenciesControllerTest extends TestCase
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
    public function coinIdNotProvided()
    {
        $response = $this->post('/api/coin/buy', ['wallet_id' => '2', 'amount_usd' => 5]);

        $response
            ->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertExactJson(['error' => 'Coin id missing from request']);
    }

    /**
     * @test
     */
    public function walletIdNotProvided()
    {
        $response = $this->post('/api/coin/buy', ['coin_id' => '2', 'amount_usd' => 5]);

        $response
            ->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertExactJson(['error' => 'Wallet id missing from request']);
    }

    /**
     * @test
     */
    public function amountNotProvided()
    {
        $response = $this->post('/api/coin/buy', ['coin_id' => '2', 'wallet_id' => '5']);

        $response
            ->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertExactJson(['error' => 'Amount missing from request']);
    }

    /**
     * @test
     */
    public function serviceIsUnavailable()
    {
        $this->cryptoDataSource
            ->expects('findCoinById')
            ->with('2')
            ->once()
            ->andThrow(new Exception('Service unavailable'));

        $response = $this->post('/api/coin/buy', ['coin_id' => '2', 'wallet_id' => '5', 'amount_usd' => 0]);

        $response
            ->assertStatus(Response::HTTP_SERVICE_UNAVAILABLE)
            ->assertExactJson(['error' => 'Service unavailable']);
    }
}
