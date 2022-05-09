<?php

use App\Infrastructure\Controllers\BuyCryptocurrenciesController;
use App\Infrastructure\Controllers\CoinStatusController;
use App\Infrastructure\Controllers\CreateWalletController;
use App\Infrastructure\Controllers\GetUserController;
use App\Infrastructure\Controllers\IsEarlyAdopterUserController;
use App\Infrastructure\Controllers\StatusController;
use App\Infrastructure\Controllers\WalletBalanceController;
use App\Infrastructure\Controllers\WalletCryptocurrenciesController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::get(
    '/status',
    StatusController::class
);

Route::get('user/{email}', IsEarlyAdopterUserController::class);
Route::get('user/id/{userId}', GetUserController::class);

Route::get('coin/status/{coin_id}', CoinStatusController::class);

Route::get('wallet/{wallet_id}', WalletCryptocurrenciesController::class);

Route::post('coin/buy', BuyCryptocurrenciesController::class);

Route::get('wallet/{wallet_id}/balance', WalletBalanceController::class);

Route::post('wallet/open', CreateWalletController::class);

