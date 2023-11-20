<?php

namespace Hen8y\Vpay;

use Illuminate\Support\ServiceProvider;
use  Hen8y\Vpay\App\Http\Middleware\VerifyWebhook;
use Hen8y\Vpay\App\Commands\Publish;




class VpayServiceProvider extends ServiceProvider
{
    public function boot():void
    {
        $this->loadViewsFrom(__DIR__.'/../src/resources/views', 'Vpay');
 
        $this->publishes([
            __DIR__.'/../src/resources/views' => resource_path('views/checkoutpage.blade.php'),

        ]);


        $this->loadRoutesFrom(__DIR__."/../src/routes/web.php");
        $this->publishes([

            __DIR__.'/../src/resources/config/vpay.php' => config_path('vpay.php')
        ]);





    }

    public function register(){
        $this->mergeConfigFrom(__DIR__."/../src/resources/config/vpay.php","vpay");

        app('router')->aliasMiddleware('VerifyWebhook',VerifyWebhook::class);

        $this->app->singleton("make:vpay", function ($app) {
            return new Publish();
        });

        $this->commands([
            "make:vpay",
        ]);

    }
}