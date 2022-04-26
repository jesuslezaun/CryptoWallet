<?php

namespace Tests\App\Application\WalletCryptocurrencies;

use App\Application\CryptoDataSource\CryptoDataSource;
use App\Domain\Coin;
use Exception;
use Mockery;
use PHPUnit\Framework\TestCase;
use App\Application\WalletCryptocurrencies\WalletCryptocurrenciesService;

class WalletCryptocurrenciesServiceTest extends TestCase
{
    private WalletCryptocurrenciesService $walletCryptoService;
    private CryptoDataSource $cryptoDataSource;

    /**
     * @setUp
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->cryptoDataSource = Mockery::mock(CryptoDataSource::class);

        $this->walletCryptoService = new WalletCryptocurrenciesService($this->cryptoDataSource);
    }

    /**
     * @test
     */
    public function walletNotFoundForGivenId()
    {
        $wallet_id = "999";

        $this->cryptoDataSource
            ->expects('findWalletCryptocurrenciesById')
            ->with($wallet_id)
            ->once()
            ->andThrow(new Exception('A wallet with the specified id was not found'));

        $this->expectException(Exception::class);

        $this->walletCryptoService->getWalletCryptocurrencies($wallet_id);
    }

    /**
     * @test
     */
    public function serviceIsUnavailable()
    {
        $wallet_id = "1";

        $this->cryptoDataSource
            ->expects('findWalletCryptocurrenciesById')
            ->with($wallet_id)
            ->once()
            ->andThrow(new Exception('Service unavailable'));

        $this->expectException(Exception::class);

        $this->walletCryptoService->getWalletCryptocurrencies($wallet_id);
    }

    /**
     * @test
     * @throws Exception
     */
    public function walletItEmpty()
    {
        $wallet_id = "2";

        $this->cryptoDataSource
            ->expects('findWalletCryptocurrenciesById')
            ->with($wallet_id)
            ->once()
            ->andReturn([]);

        $walletCoins = $this->walletCryptoService->getWalletCryptocurrencies($wallet_id);

        $this->assertEquals([], $walletCoins);
    }
}
