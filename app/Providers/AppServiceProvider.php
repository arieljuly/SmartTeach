<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Socialite\Facades\Socialite;
use GuzzleHttp\Client;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
         if ($this->app->environment('local')) {
            Socialite::extend('google', function ($app) {
                $config = $app['config']['services.google'];
                
                $provider = new \Laravel\Socialite\Two\GoogleProvider(
                    $app['request'],
                    $config['client_id'],
                    $config['client_secret'],
                    $config['redirect']
                );
                
                // Set custom Guzzle client
                $provider->setHttpClient(new Client([
                    'verify' => 'C:/xampp/apache/bin/curl-ca-bundle.crt',
                    'timeout' => 30,
                ]));
                
                return $provider;
            });
        }
    }
}
