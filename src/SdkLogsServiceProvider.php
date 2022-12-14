<?php

namespace LogManager\SdkLogs;

use Illuminate\Support\ServiceProvider;

class SdkLogsServiceProvider extends ServiceProvider 
{
    public function boot()
    {
        $this->mergeConfigFrom(__DIR__.'/../src/config/config.php', 'sdk-logs');
    }

    public function register()
    {
        $this->app->bind('SDKLogs', function($app) {
            return new SDKLogs();
        });

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../src/config/config.php' => config_path('sdk-logs.php'),
            ], 'config');
        }
    }
}