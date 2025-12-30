<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;


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
    RateLimiter::for('public-read', function (Request $request) {
    $client = (string) $request->header('X-Client', 'unknown');
    return Limit::perMinute(120)->by($client.'|'.$request->ip());
    });

    RateLimiter::for('public-calc', function (Request $request) {
        $client = (string) $request->header('X-Client', 'unknown');
        return Limit::perMinute(30)->by($client.'|'.$request->ip());
    });

    RateLimiter::for('public-book', function (Request $request) {
        $client = (string) $request->header('X-Client', 'unknown');
        return Limit::perMinute(10)->by($client.'|'.$request->ip());
    });

    }
}
