<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Auth\CustomTokenRepository;
use Illuminate\Auth\Passwords\PasswordBroker;
use Illuminate\Contracts\Hashing\Hasher;

class PasswordResetServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(CustomTokenRepository::class, function ($app) {
            $config = $app['config']['auth.passwords.users'];

            return new CustomTokenRepository(
                $app['db']->connection(),
                $app->make(Hasher::class),
                $config['table'],
                $app['config']['app.key'],
                $config['expire'] * 60
            );
        });

                $this->app->extend('auth.password', function ($service, $app) {
            $config = $app['config']['auth.passwords.users'];

            return new PasswordBroker(
                $app->make(\App\Auth\CustomTokenRepository::class), // tokens
                $app['auth']->createUserProvider($config['provider']), // user provider
                $app['events'], // dispatcher
                null, // token model (biarkan null)
                $config['expire'], // token expiration (menit)
                60 // throttle
            );
        });


    }

    public function boot(): void
    {
        //
    }
}
