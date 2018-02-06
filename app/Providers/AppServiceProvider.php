<?php

namespace App\Providers;


use App\Admin\Extensions\ZaneConfig;
use App\Models\Category;
use Encore\Admin\Config\Config;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Config::load();
        view()->share([
            'url' => request()->url(),
            'categories' => Category::query()->get()
        ]);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
