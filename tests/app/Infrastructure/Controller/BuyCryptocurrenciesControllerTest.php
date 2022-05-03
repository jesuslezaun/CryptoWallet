<?php

namespace Tests\app\Infrastructure\Controller;

use App\Application\CryptoDataSource\CryptoDataSource;
use App\Application\CryptoDataStorage\CryptoDataStorage;
use App\Infrastructure\Controllers\BuyCryptocurrenciesController;
use Exception;
use Illuminate\Http\Response;
use Mockery;
use Tests\TestCase;

class BuyCryptocurrenciesControllerTest extends TestCase
{
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

        $this->app->bind(CryptoDataSource::class, fn () => $this->cryptoDataSource);
        $this->app->bind(CryptoDataStorage::class, fn () => $this->cryptoDataStorage);
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

    /**
     * @test
     */
    public function coinNotFoundForGivenId()
    {
        $this->cryptoDataSource
            ->expects('findCoinById')
            ->with('999')
            ->once()
            ->andThrow(new Exception('A coin with the specified id was not found'));

        $response = $this->post('/api/coin/buy', ['coin_id' => '999', 'wallet_id' => '5', 'amount_usd' => 0]);

        $response
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertExactJson(['error' => 'A coin with the specified id was not found']);
    }

    /**
     * @test
     */
    public function walletNotFoundForGivenId()
    {
        $this->cryptoDataSource
            ->expects('findCoinById')
            ->with('999')
            ->once();

        $this->cryptoDataStorage
            ->expects('getWalletById')
            ->with('999')
            ->once()
            ->andThrow(new Exception('A wallet with the specified id was not found'));

        $response = $this->post('/api/coin/buy', ['coin_id' => '999', 'wallet_id' => '999', 'amount_usd' => 0]);

        $response
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertExactJson(['error' => 'A wallet with the specified id was not found']);
    }

    /**
     * @test
     */
    public function amountNotPositive()
    {
        $this->cryptoDataSource
            ->expects('findCoinById')
            ->with('999')
            ->once();

        $this->cryptoDataStorage
            ->expects('getWalletById')
            ->with('999')
            ->once();

        $response = $this->post('/api/coin/buy', ['coin_id' => '999', 'wallet_id' => '999', 'amount_usd' => 0]);

        $response
            ->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertExactJson(['error' => 'Amount should be positive']);
    }
}
