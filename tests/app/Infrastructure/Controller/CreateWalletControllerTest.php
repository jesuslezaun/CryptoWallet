<?php

namespace Tests\app\Infrastructure\Controller;

use App\Application\CryptoDataStorage\CryptoDataStorage;
use App\Domain\Wallet;
use Exception;
use Illuminate\Http\Response;
use Mockery;
use Tests\TestCase;

class CreateWalletControllerTest extends TestCase
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
    public function serviceIsUnavailable()
    {
        $this->cryptoDataStorage
            ->expects('createWallet')
            ->once()
            ->andThrow(new Exception('Service unavailable'));

        $response = $this->post('/api/wallet/open');

        $response
            ->assertStatus(Response::HTTP_SERVICE_UNAVAILABLE)
            ->assertExactJson(['error' => 'Service unavailable']);
    }

    /**
     * @test
     */
    public function callReturnsWalletId()
    {
        $user_wallet = new Wallet('0');

        $this->cryptoDataStorage
            ->expects('createWallet')
            ->once()
            ->andReturn($user_wallet);

        $response = $this->post('/api/wallet/open');

        $response->assertStatus(Response::HTTP_OK)->assertExactJson(['wallet_id' => '0']);
    }
}
