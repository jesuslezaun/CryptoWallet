<?php

namespace Tests\App\Application\CreateWallet;

use App\Application\CreateWallet\CreateWalletService;
use App\Application\CryptoDataStorage\CryptoDataStorage;
use App\Domain\Wallet;
use Exception;
use Mockery;
use PHPUnit\Framework\TestCase;

class CreateWalletServiceTest extends TestCase
{
    private CreateWalletService $createWalletService;
    private CryptoDataStorage $cryptoDataStorage;

    /**
     * @setUp
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->cryptoDataStorage = Mockery::mock(CryptoDataStorage::class);

        $this->createWalletService = new CreateWalletService($this->cryptoDataStorage);
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

        $this->expectException(Exception::class);

        $this->createWalletService->execute();
    }

    /**
     * @test
     */
    public function callReturnsWalletId()
    {
        $wallet_id = "1";
        $user_wallet = new Wallet($wallet_id);

        $this->cryptoDataStorage
            ->expects('createWallet')
            ->once()
            ->andReturn($user_wallet);

        $userWalletId = $this->createWalletService->execute();

        $this->assertEquals($wallet_id, $userWalletId);
    }
}
