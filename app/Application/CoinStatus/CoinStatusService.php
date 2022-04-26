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
     * @param int $coinId
     * @return CoinStatus
     * @throws Exception
     */
    public function getCoinStatus(int $coinId): CoinStatus
    {
        return $this->cryptoDataSource->findCoinByID($coinId);
    }
}
