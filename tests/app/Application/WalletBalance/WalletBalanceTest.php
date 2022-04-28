<?php

namespace Tests\App\Application\Balance;

use App\Application\CryptoDataStorage\CryptoDataStorage;
use App\Domain\Coin;
use App\Domain\Wallet;
use Exception;
use Mockery;
use PHPUnit\Framework\TestCase;
use App\Application\WalletCryptocurrencies\WalletCryptocurrenciesService;

class WalletBalanceTest extends TestCase
{
    private WalletCryptocurrenciesService $walletCryptoService;
    private CryptoDataStorage $cryptoDataStorage;

    /**
     * @setUp
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->cryptoDataStorage = Mockery::mock(CryptoDataStorage::class);

        $this->walletCryptoService = new WalletCryptocurrenciesService($this->cryptoDataStorage);
    }

    /**
     * @test
     */
    public function walletNotFoundForGivenId()
    {
        $wallet_id = "999";

        $this->cryptoDataStorage
            ->expects('getWalletById')
            ->with($wallet_id)
            ->once()
            ->andThrow(new Exception('A wallet with the specified id was not found'));

        $this->expectException(Exception::class);

        $this->walletCryptoService->getWalletCryptocurrencies($wallet_id);
    }
}
