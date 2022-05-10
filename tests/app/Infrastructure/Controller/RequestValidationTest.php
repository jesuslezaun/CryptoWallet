<?php

namespace Tests\app\Infrastructure\Controller;

use App\Infrastructure\Controllers\RequestValidation;
use Illuminate\Http\Request;
use PHPUnit\Framework\TestCase;

class RequestValidationTest extends TestCase
{
    private RequestValidation $requestValidation;

    /**
     * @setUp
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->requestValidation = new RequestValidation();
    }

    /**
     * @test
     */
    public function coinIdNotProvided()
    {
        $request = new Request(['wallet_id' => '999', 'amount_usd' => 5]);

        $response = $this->requestValidation->validate($request);

        $this->assertEquals("Coin id missing from request", $response);
    }

    /**
     * @test
     */
    public function walletIdNotProvided()
    {
        $request = new Request(['coin_id' => '2', 'amount_usd' => 5]);

        $response = $this->requestValidation->validate($request);

        $this->assertEquals("Wallet id missing from request", $response);
    }

    /**
     * @test
     */
    public function amountNotProvided()
    {
        $request = new Request(['coin_id' => '2', 'wallet_id' => '5']);

        $response = $this->requestValidation->validate($request);

        $this->assertEquals("Amount missing from request", $response);
    }

    /**
     * @test
     */
    public function properRequest()
    {
        $request = new Request(['coin_id' => '2', 'wallet_id' => '5', 'amount_usd' => 5]);

        $response = $this->requestValidation->validate($request);

        $this->assertEquals("", $response);
    }
}
