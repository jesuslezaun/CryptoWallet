<?php

namespace Tests\app\Infrastructure\Controller;

use App\Infrastructure\Controllers\BuyCryptocurrenciesController;
use Illuminate\Http\Response;
use Tests\TestCase;

class BuyCryptocurrenciesControllerTest extends TestCase
{
    /**
     * @test
     */
    public function coinIdNotProvided()
    {
        $response = $this->post('/api/coin/buy', ['wallet_id' => '2', 'amount_usd' => 5]);

        $response
            ->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertExactJson(['error' => 'Coin id missing from request']);
    }

    /**
     * @test
     */
    public function walletIdNotProvided()
    {
        $response = $this->post('/api/coin/buy', ['coin_id' => '2', 'amount_usd' => 5]);

        $response
            ->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertExactJson(['error' => 'Wallet id missing from request']);
    }
}
