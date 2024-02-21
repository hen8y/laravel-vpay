<?php

namespace Hen8y\Vpay;

use Illuminate\Support\ServiceProvider;
use  Hen8y\Vpay\App\Http\Middleware\VerifyWebhook;
use Hen8y\Vpay\App\Commands\Publish;




class VpayServiceProvider extends ServiceProvider
{
    public function boot():void
    {

        $config = realpath(__DIR__."/../config/vpay.php");
        $this->loadRoutesFrom(__DIR__."/routes/web.php");
        $this->publishes([

            $config => config_path('vpay.php')
        ],"vpay-config");





    }

    public function register(){
        $config = realpath(__DIR__."/../config/vpay.php");
        
        $this->mergeConfigFrom($config,"vpay");

        app('router')->aliasMiddleware('VerifyWebhook',VerifyWebhook::class);

        $this->app->singleton("vpay:publish", function ($app) {
            return new Publish();
        });

        $this->commands([
            "vpay:publish",
        ]);

    }
}