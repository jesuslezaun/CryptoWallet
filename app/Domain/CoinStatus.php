<?php

namespace App\Domain;

class CoinStatus
{
    private string $coin_id;
    private string $symbol;
    private string $name;
    private string $name_id;
    private int $rank;
    private string $price_usd;

    /**
     * @param string $coin_id
     * @param string $symbol
     * @param string $name
     * @param string $name_id
     * @param int $rank
     * @param string $price_usd
     */
    public function __construct(
        string $coin_id,
        string $symbol,
        string $name,
        string $name_id,
        int $rank,
        string $price_usd
    ) {
        $this->coin_id = $coin_id;
        $this->symbol = $symbol;
        $this->name = $name;
        $this->name_id = $name_id;
        $this->rank = $rank;
        $this->price_usd = $price_usd;
    }

    /**
     * @return string
     */
    public function getCoinId(): string
    {
        return $this->coin_id;
    }

    /**
     * @return string
     */
    public function getSymbol(): string
    {
        return $this->symbol;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getNameId(): string
    {
        return $this->name_id;
    }

    /**
     * @return int
     */
    public function getRank(): int
    {
        return $this->rank;
    }

    /**
     * @return string
     */
    public function getPriceUsd(): string
    {
        return $this->price_usd;
    }
}
