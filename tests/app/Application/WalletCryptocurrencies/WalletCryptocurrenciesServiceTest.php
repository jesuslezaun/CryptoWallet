<?php

namespace Tests\App\Application\WalletCryptocurrencies;

use App\Application\CryptoDataSource\CryptoDataSource;
use App\Application\CryptoDataStorage\CryptoDataStorage;
use App\Domain\Coin;
use App\Domain\Wallet;
use Exception;
use Mockery;
use PHPUnit\Framework\TestCase;
use App\Application\WalletCryptocurrencies\WalletCryptocurrenciesService;

class WalletCryptocurrenciesServiceTest extends TestCase
{
    private WalletCryptocurrenciesService $walletCryptoService;
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

        $this->walletCryptoService =
            new WalletCryptocurrenciesService($this->cryptoDataStorage, $this->cryptoDataSource);
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

    /**
     * @test
     */
    public function serviceIsUnavailable()
    {
        $wallet_id = "1";

        $this->cryptoDataStorage
            ->expects('getWalletById')
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
    public function walletIsEmpty()
    {
        $wallet_id = "2";
        $userWallet = new Wallet($wallet_id);

        $this->cryptoDataStorage
            ->expects('getWalletById')
            ->with($wallet_id)
            ->once()
            ->andReturn($userWallet);

        $walletCoins = $this->walletCryptoService->getWalletCryptocurrencies($wallet_id);

        $this->assertEquals([], $walletCoins);
    }

    /**
     * @test
     * @throws Exception
     */
    public function callReturnsWalletCryptocurrencies()
    {
        $wallet_id = "2";
        $coin1 = new Coin("90", "Bitcoin", "BTC", 10, 6010);
        $coin2 = new Coin("80", "Ethereum", "ETH", 10, 1000);
        $userWallet = new Wallet($wallet_id);
        $userWallet->insertCoin($coin1);
        $userWallet->insertCoin($coin2);

        $this->cryptoDataStorage
            ->expects('getWalletById')
            ->with($wallet_id)
            ->once()
            ->andReturns($userWallet);

        $this->cryptoDataSource
            ->expects('getCoinUsdValueById')
            ->twice()
            ->andReturnValues([6010, 1000]);

        $walletCoins = $this->walletCryptoService->getWalletCryptocurrencies($wallet_id);

        $this->assertEquals([$coin1, $coin2], $walletCoins);
    }
}
