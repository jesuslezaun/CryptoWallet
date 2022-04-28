<?php

namespace Tests\app\Infrastructure\Controller;

use App\Application\CryptoDataStorage\CryptoDataStorage;
use App\Domain\Coin;
use App\Domain\Wallet;
use Exception;
use Illuminate\Http\Response;
use Mockery;
use Tests\TestCase;

class WalletBalanceControllerTest extends TestCase
{
    private CryptoDataStorage $cryptoDataStorage;

    /**
     * @setUp
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->cryptoDataStorage = Mockery::mock(CryptoDataStorage::class);
        $this->app->bind(CryptoDataStorage::class, fn () => $this->cryptoDataStorage);
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

        $response = $this->get('/api/wallet/999/balance');

        $response
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertExactJson(['error' => 'A wallet with the specified id was not found']);
    }
}
