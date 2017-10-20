<?php

namespace Boneq\OneNet;

use Illuminate\Support\ServiceProvider;

class OneNetServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/onenet.php' => config_path('onenet.php'),
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('onenet',function ($app){
            return new OneNet($app);
        });
    }
}
