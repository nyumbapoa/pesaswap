<?php

namespace Nyumbapoa\Pesaswap;


use Nyumbapoa\Pesaswap\Console\InstallPesaswapPackage;
use Illuminate\Support\ServiceProvider;


class PesaswapServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('nyumbapoa-pesaswap', function ($app) {
            return new Pesaswap;
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        if ($this->app->runningInConsole()) {

            $this->publishes([
                __DIR__.'/../config/pesaswap.php' => config_path('pesaswap.php'),
            ], 'pesaswap-config');

            $this->commands([
                InstallPesaswapPackage::class
            ]);
        }
    }
}