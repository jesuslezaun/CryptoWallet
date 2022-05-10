<?php

namespace App\Infrastructure\Providers;

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
//        $this->app->bind(UserDataSource::class, function () {
//            return new EloquentUserDataSource();
//        });
        $this->app->bind(CryptoDataStorage::class, function () {
            return new CacheCryptoDataStorage();
        });
    }
}
