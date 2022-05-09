<?php

namespace Tests\App\Application\CryptoDataStorage;

use App\Application\CryptoDataStorage\CacheCryptoDataStorage;
use App\Application\CryptoDataStorage\CryptoDataStorage;
use App\Domain\Wallet;
use Exception;
use Tests\TestCase;
use Illuminate\Support\Facades\Cache;

class CacheCryptoDataStorageTest extends TestCase
{
    private CryptoDataStorage $cacheStorage;

    /**
     * @setUp
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->cacheStorage = new CacheCryptoDataStorage();
    }

    /**
     * @test
     */
    public function newWalletCreated()
    {
        Cache::forget("1");

        $walletCreated = $this->cacheStorage->createWallet();

        $this->assertEquals("1", $walletCreated->getWalletId());
    }

    /**
     * @test
     */
    public function walletWithSpecificIdWasNotFound()
    {
        $this->expectException(Exception::class);

        $this->cacheStorage->getWalletById("99");
    }

    /**
     * @test
     */
    public function walletWithSpecificIdFound()
    {
        $expectedWallet = $this->cacheStorage->createWallet();

        $returnedWallet = $this->cacheStorage->getWalletById($expectedWallet->getWalletId());

        $this->assertEquals($expectedWallet, $returnedWallet);
    }
}
