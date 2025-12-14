<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider; // INI YANG BENAR UNTUK LARAVEL 11

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
        // RATE LIMITER UNTUK LOGIN (WAJIB!)
        RateLimiter::for('login', function (Request $request) {
            $login = $request->input('login') ?? $request->ip();
            return Limit::perMinute(5)->by($login . '|' . $request->ip());
        });

        // RATE LIMITER UNTUK TWO-FACTOR (WAJIB!)
        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id') ?? $request->ip());
        });

        // RATE LIMITER UNTUK API (opsional)
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}