<?php

namespace App\Providers;

use Auth, DB, Log;
use Illuminate\Support\ServiceProvider;
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
        // DB::listen(function($query) { Log::info("[$query->time ms] " . $query->sql, $query->bindings); });

        view()->composer('*', function ($view) { $view->with('authUser', Auth::user()); });
        Schema::defaultStringLength(191);
    }
}
