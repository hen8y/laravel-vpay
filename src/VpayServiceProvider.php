<?php

namespace Hen8y\Vpay;

use Illuminate\Support\ServiceProvider;
use Hen8y\Vpay\App\Http\Middleware\VerifyWebhook;
use Hen8y\Vpay\App\Commands\Publish;

class VpayServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . "/routes/web.php");
        $this->publishes(
            [realpath(__DIR__ . "/../config/vpay.php") => config_path('vpay.php')],
            "vpay-config"
        );
    }

    public function register()
    {
        $this->mergeConfigFrom(realpath(__DIR__ . "/../config/vpay.php"), "vpay");

        app('router')->aliasMiddleware('VerifyWebhook', VerifyWebhook::class);

        $this->app->singleton("vpay:publish", fn() => new Publish());

        $this->commands(["vpay:publish"]);
    }
}
