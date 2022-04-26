<?php

namespace App\Application\CoinStatus;

use App\Application\CryptoDataSource\CryptoDataSource;
use App\Domain\CoinStatus;
use Exception;

class CoinStatusService
{
    /**
     * @var CryptoDataSource
     */
    private CryptoDataSource $cryptoDataSource;

    /**
     * CoinStatusService constructor.
     * @param CryptoDataSource $cryptoDataSource
     */
    public function __construct(CryptoDataSource $cryptoDataSource)
    {
        $this->cryptoDataSource = $cryptoDataSource;
    }

    /**
     * @param string $coinId
     * @return CoinStatus
     * @throws Exception
     */
    public function getCoinStatus(string $coinId): CoinStatus
    {
        return $this->cryptoDataSource->findCoinStatusById($coinId);
    }
}
