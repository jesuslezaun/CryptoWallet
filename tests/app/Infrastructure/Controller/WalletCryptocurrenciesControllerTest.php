<?php

namespace Tests\app\Infrastructure\Controller;

use App\Application\CryptoDataSource\CryptoDataSource;
use App\Domain\Coin;
use Exception;
use Illuminate\Http\Response;
use Mockery;
use Tests\TestCase;

class WalletCryptocurrenciesControllerTest extends TestCase
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
    public function walletNotFoundForGivenId()
    {
        $this->cryptoDataSource
            ->expects('findWalletCryptocurrenciesById')
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
        $this->cryptoDataSource
            ->expects('findWalletCryptocurrenciesById')
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
        $this->cryptoDataSource
            ->expects('findWalletCryptocurrenciesById')
            ->with('2')
            ->once()
            ->andReturn([]);

        $response = $this->get('/api/wallet/2');

        $response->assertStatus(Response::HTTP_OK)->assertExactJson([]);
    }

    /**
     * @test
     */
    public function callReturnsWalletCoins()
    {
        $coin1 = new Coin("90", "Bitcoin", "BTC", 10, 6010);
        $coin2 = new Coin("80", "Ethereum", "ETH", 10, 1000);

        $this->cryptoDataSource
            ->expects('findWalletCryptocurrenciesById')
            ->with('2')
            ->once()
            ->andReturn([$coin1, $coin2]);

        $response = $this->get('/api/wallet/2');

        $response
            ->assertStatus(Response::HTTP_OK)
            ->assertExactJson([['coin_id' => "90", 'name' => 'Bitcoin', 'symbol' => 'BTC',
                'amount' => 10, 'value_usd' => 6010],
                ['coin_id' => "80", 'name' => 'Ethereum', 'symbol' => 'ETH',
                'amount' => 10, 'value_usd' => 1000]]);
    }
}
