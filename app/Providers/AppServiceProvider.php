<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
// https://laravel-news.com/laravel-5-4-key-too-long-error
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
			// Fixing the "Syntax error or access violation: 1071 Specified key was too long; max key length is 767 bytes" issue
			Schema::defaultStringLength(191);
    }
}
