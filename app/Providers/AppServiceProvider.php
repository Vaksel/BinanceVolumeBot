<?php

namespace App\Providers;

use Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
//        // You can also clear cache if needed Artisan::call('cache:clear');
//        Artisan::call('optimize:clear');

        if(in_array(Request::ip(), ['81.162.252.146'])) {
            config(['app.debug' => true]);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
