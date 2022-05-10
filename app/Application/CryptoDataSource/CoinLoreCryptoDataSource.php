<?php

namespace App\Application\CryptoDataSource;

use App\Domain\Coin;
use App\Domain\CoinStatus;

class CoinLoreCryptoDataSource implements CryptoDataSource
{
    public function findCoinStatusById(string $coinId): CoinStatus
    {
        $genericCoinData = $this->getGenericCoinData($coinId);
        return new CoinStatus(
            $genericCoinData[0]->id,
            $genericCoinData[0]->symbol,
            $genericCoinData[0]->name,
            $genericCoinData[0]->nameid,
            $genericCoinData[0]->rank,
            $genericCoinData[0]->price_usd,
        );
    }

    public function findCoinById(string $coinId): Coin
    {
        $genericCoinData = $this->getGenericCoinData($coinId);
        $coinAmount = 0;
        return new Coin(
            $genericCoinData[0]->id,
            $genericCoinData[0]->name,
            $genericCoinData[0]->symbol,
            $coinAmount,
            $genericCoinData[0]->price_usd,
        );
    }

    public function getCoinUsdValueById(string $coindId): float
    {
        $genericCoinData = $this->getGenericCoinData($coindId);
        return $genericCoinData[0]->price_usd;
    }

    private function getGenericCoinData(string $coinId): array
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.coinlore.net/api/ticker/?id=$coinId",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ]);

        $response = curl_exec($curl);
        curl_close($curl);

        return json_decode($response);
    }
}
