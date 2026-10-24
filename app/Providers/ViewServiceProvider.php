<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    public function boot()
    {
        View::composer('*', function ($view) {
            $config = cache()->rememberForever('setting_webs', function () {
                return DB::table('setting_webs')->where('id', 1)->first();
            });

            $view->with('config', $config);
        });
    }
}
