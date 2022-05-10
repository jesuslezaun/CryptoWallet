<?php

namespace App\Infrastructure\Providers;

use App\Application\CryptoDataSource\CoinLoreCryptoDataSource;
use App\Application\CryptoDataSource\CryptoDataSource;
use App\Application\CryptoDataStorage\CacheCryptoDataStorage;
use App\Application\CryptoDataStorage\CryptoDataStorage;
use App\Application\UserDataSource\UserDataSource;
use App\DataSource\Database\EloquentUserDataSource;
use Illuminate\Cache\Events\CacheHit;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(CryptoDataSource::class, function () {
            return new CoinLoreCryptoDataSource();
        });

        $this->app->bind(CryptoDataStorage::class, function () {
            return new CacheCryptoDataStorage();
        });
    }
}
