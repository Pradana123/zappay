<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use App\Models\SettingWeb;
use App\Models\Pembelian;
use App\Observers\PembelianObserver;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            return;
        }

        $config = Cache::rememberForever('setting_webs', function () {
            return SettingWeb::find(1);
        });

        View::share('config', $config);

        Pembelian::observe(PembelianObserver::class);
    }
}
