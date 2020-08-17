<?php

namespace Qihucms\TongGuanPay;

use Illuminate\Support\ServiceProvider;

class TongGuanPayServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton("TongGuanPay", function () {
            return new Tongguan();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/tongguan.php' => config_path('tongguan.php'),
        ], 'tongguan');
    }
}
