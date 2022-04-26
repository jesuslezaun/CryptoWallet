<?php

namespace Tests\app\Infrastructure\Controller;

use App\Application\CryptoDataSource\CryptoDataSource;
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
}
