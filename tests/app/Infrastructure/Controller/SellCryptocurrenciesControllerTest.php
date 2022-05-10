<?php

namespace Tests\app\Infrastructure\Controller;

use App\Application\CryptoDataSource\CryptoDataSource;
use App\Application\CryptoDataStorage\CryptoDataStorage;
use App\Domain\Coin;
use App\Domain\Wallet;
use Illuminate\Http\Response;
use Mockery;
use Tests\TestCase;
use Exception;

class SellCryptocurrenciesControllerTest extends TestCase
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
        $response = $this->post('/api/coin/sell', ['coin_id' => '2', 'amount_usd' => 5]);

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
            ->with('3')
            ->once()
            ->andThrow(new Exception('Service unavailable'));

        $response = $this->post('/api/coin/sell', ['coin_id' => '3', 'wallet_id' => '5', 'amount_usd' => 0]);

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
            ->with('99')
            ->once()
            ->andThrow(new Exception('A coin with the specified id was not found'));

        $response = $this->post('/api/coin/sell', ['coin_id' => '99', 'wallet_id' => '5', 'amount_usd' => 0]);

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
            ->with('99')
            ->once();

        $this->cryptoDataStorage
            ->expects('getWalletById')
            ->with('99')
            ->once()
            ->andThrow(new Exception('A wallet with the specified id was not found'));

        $response = $this->post('/api/coin/sell', ['coin_id' => '99', 'wallet_id' => '99', 'amount_usd' => 0]);

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
            ->with('99')
            ->once();
        $this->cryptoDataStorage
            ->expects('getWalletById')
            ->with('99')
            ->once();

        $response = $this->post('/api/coin/sell', ['coin_id' => '99', 'wallet_id' => '99', 'amount_usd' => 0]);

        $response
            ->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertExactJson(['error' => 'Amount should be positive']);
    }

    /**
     * @test
     */
    public function coinNotInWallet()
    {
        $coin = new Coin("90", "Bitcoin", "BTC", 1, 6010);
        $wallet = new Wallet("10");
        $this->cryptoDataSource
            ->expects('findCoinById')
            ->with('90')
            ->once()
            ->andReturn($coin);
        $this->cryptoDataStorage
            ->expects('getWalletById')
            ->with('10')
            ->once()
            ->andReturn($wallet);

        $response = $this->post('/api/coin/sell', ['coin_id' => '90', 'wallet_id' => '10', 'amount_usd' => 1]);

        $response
            ->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertExactJson(['error' => 'Coin is not in wallet']);
    }

    /**
     * @test
     */
    public function notEnoughCoinsInWallet()
    {
        $coin = new Coin("90", "Bitcoin", "BTC", 1, 6010);
        $wallet = new Wallet("10");
        $wallet->insertCoin($coin);

        $this->cryptoDataSource
            ->expects('findCoinById')
            ->with('90')
            ->once()
            ->andReturn($coin);
        $this->cryptoDataStorage
            ->expects('getWalletById')
            ->with('10')
            ->once()
            ->andReturn($wallet);

        $response = $this->post('/api/coin/sell', ['coin_id' => '90', 'wallet_id' => '10', 'amount_usd' => 6011]);

        $response
            ->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertExactJson(['error' => 'No enough coins to sell']);
    }

    /**
     * @test
     */
    public function coinSell()
    {
        $coin = new Coin("90", "Bitcoin", "BTC", 1, 6010);
        $wallet = new Wallet("1");
        $wallet->insertCoin($coin);

        $this->cryptoDataSource
            ->expects('findCoinById')
            ->with('90')
            ->once()
            ->andReturn($coin);
        $this->cryptoDataStorage
            ->expects('getWalletById')
            ->with('1')
            ->once()
            ->andReturn($wallet);
        $this->cryptoDataStorage
            ->expects('updateWallet')
            ->once();

        $response = $this->post('/api/coin/sell', ['coin_id' => '90', 'wallet_id' => '1', 'amount_usd' => 6010]);

        $response
            ->assertStatus(Response::HTTP_OK)
            ->assertExactJson([]);
    }
}
