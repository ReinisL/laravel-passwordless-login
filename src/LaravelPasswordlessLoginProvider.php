<?php

namespace Grosv\LaravelPasswordlessLogin;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class LaravelPasswordlessLoginProvider extends ServiceProvider
{
    public function boot()
    {
        $this->registerRoutes();

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/laravel-passwordless-login.php' => config_path('laravel-passwordless-login.php'),
            ], 'passwordless-login-config');
        }
    }

    /**
     * Register the Horizon routes.
     *
     * @return void
     */
    protected function registerRoutes()
    {
        Route::group([
            'middleware' => config('laravel-passwordless-login.middleware', [HandleAuthenticatedUsers::class]),
        ], function () {
            $this->loadRoutesFrom(__DIR__.'/routes.php');
        });
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/laravel-passwordless-login.php', 'laravel-passwordless-login');

        $this->app->singleton('passwordless-login', function ($app) {
            return new PasswordlessLoginManager();
        });
    }
}
