<?php

namespace App\Application\WalletCryptocurrencies;

use App\Application\CryptoDataSource\CryptoDataSource;
use App\Domain\Wallet;
use Exception;

class WalletCryptocurrenciesService
{
    /**
     * @var CryptoDataSource
     */
    private CryptoDataSource $cryptoDataSource;

    /**
     * WalletCryptocurrenciesService constructor.
     * @param CryptoDataSource $cryptoDataSource
     */
    public function __construct(CryptoDataSource $cryptoDataSource)
    {
        $this->cryptoDataSource = $cryptoDataSource;
    }

    /**
     * @param string $wallet_id
     * @return array
     * @throws Exception
     */
    public function getWalletCryptocurrencies(string $wallet_id): array
    {
        return $this->cryptoDataSource->findWalletCryptocurrenciesById($wallet_id);
    }
}
