<?php

namespace App\Infrastructure\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RequestValidation
{
    public function validate(Request $request): string
    {
        if (!($request->has('coin_id'))) {
            return 'Coin id missing from request';
        }
        if (!($request->has('wallet_id'))) {
            return 'Wallet id missing from request';
        }
        if (!($request->has('amount_usd'))) {
            return 'Amount missing from request';
        }

        return "";
    }
}
