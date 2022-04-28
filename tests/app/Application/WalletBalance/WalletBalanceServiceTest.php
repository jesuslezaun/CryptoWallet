<?php

namespace Tests\App\Application\Balance;

use App\Application\CryptoDataStorage\CryptoDataStorage;
use App\Application\WalletBalance\WalletBalanceService;
use App\Domain\Coin;
use App\Domain\Wallet;
use Exception;
use Mockery;
use PHPUnit\Framework\TestCase;

class WalletBalanceServiceTest extends TestCase
{
    private WalletBalanceService $walletBalanceService;
    private CryptoDataStorage $cryptoDataStorage;

    /**
     * @setUp
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->cryptoDataStorage = Mockery::mock(CryptoDataStorage::class);

        $this->walletBalanceService = new WalletBalanceService($this->cryptoDataStorage);
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

        $this->walletBalanceService->getWalletBalance($wallet_id);
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

        $this->walletBalanceService->getWalletBalance($wallet_id);
    }
}
