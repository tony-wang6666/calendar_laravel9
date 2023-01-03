<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

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
        #20230102添加的https路徑
        // if(env('FORCE_HTTPS',false)) { // Default value should be false for local server
        //     URL::forceScheme('https');
        // }
        // URL::forceScheme('https');
        if(env('APP_ENV') !== 'local') {
            URL::forceScheme('https');
        }
    }
}
