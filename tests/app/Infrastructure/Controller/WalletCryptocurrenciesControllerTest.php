<?php

namespace Tests\app\Infrastructure\Controller;

use App\Application\CryptoDataSource\CryptoDataSource;
use App\Application\CryptoDataStorage\CryptoDataStorage;
use App\Domain\Coin;
use App\Domain\Wallet;
use Exception;
use Illuminate\Http\Response;
use Mockery;
use Tests\TestCase;

class WalletCryptocurrenciesControllerTest extends TestCase
{
    private CryptoDataStorage $cryptoDataStorage;
    private CryptoDataSource $cryptoDataSource;

    /**
     * @setUp
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->cryptoDataStorage = Mockery::mock(CryptoDataStorage::class);
        $this->cryptoDataSource = Mockery::mock(CryptoDataSource::class);
        $this->app->bind(CryptoDataStorage::class, fn () => $this->cryptoDataStorage);
        $this->app->bind(CryptoDataSource::class, fn () => $this->cryptoDataSource);
    }

    /**
     * @test
     */
    public function walletNotFoundForGivenId()
    {
        $this->cryptoDataStorage
            ->expects('getWalletById')
            ->with('999')
            ->once()
            ->andThrow(new Exception('A wallet with the specified id was not found'));

        $response = $this->get('/api/wallet/999');

        $response
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertExactJson(['error' => 'A wallet with the specified id was not found']);
    }

    /**
     * @test
     */
    public function serviceIsUnavailable()
    {
        $this->cryptoDataStorage
            ->expects('getWalletById')
            ->with('2')
            ->once()
            ->andThrow(new Exception('Service unavailable'));

        $response = $this->get('/api/wallet/2');

        $response
            ->assertStatus(Response::HTTP_SERVICE_UNAVAILABLE)
            ->assertExactJson(['error' => 'Service unavailable']);
    }

    /**
     * @test
     */
    public function walletIsEmpty()
    {
        $userWallet = new Wallet("2");

        $this->cryptoDataStorage
            ->expects('getWalletById')
            ->with('2')
            ->once()
            ->andReturn($userWallet);

        $response = $this->get('/api/wallet/2');

        $response->assertStatus(Response::HTTP_OK)->assertExactJson([]);
    }

    /**
     * @test
     */
    public function callReturnsWalletCryptocurrencies()
    {
        $wallet_id = 2;
        $coin1 = new Coin("90", "Bitcoin", "BTC", 10, 6010);
        $coin2 = new Coin("80", "Ethereum", "ETH", 10, 1000);
        $userWallet = new Wallet($wallet_id);
        $userWallet->insertCoin($coin1);
        $userWallet->insertCoin($coin2);

        $this->cryptoDataStorage
            ->expects('getWalletById')
            ->with('2')
            ->once()
            ->andReturn($userWallet);

        $this->cryptoDataSource
            ->expects('getCoinUsdValueById')
            ->twice()
            ->andReturnValues([6010, 1000]);

        $response = $this->get('/api/wallet/2');

        $response
            ->assertStatus(Response::HTTP_OK)
            ->assertExactJson([['coin_id' => "90", 'name' => 'Bitcoin', 'symbol' => 'BTC',
                'amount' => 10, 'value_usd' => 6010],
                ['coin_id' => "80", 'name' => 'Ethereum', 'symbol' => 'ETH',
                'amount' => 10, 'value_usd' => 1000]]);
    }
}
