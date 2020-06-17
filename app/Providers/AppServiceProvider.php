<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

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
        Blade::component('components.text', 'text');
        Blade::component('components.textarea', 'textarea');
        Blade::component('components.switch', 'switchInput');
        Blade::component('components.date', 'date');
        Blade::component('components.textNumber', 'textNumber');
        Blade::component('components.number', 'number');
        Blade::component('components.popup', 'popup');
        Blade::component('components.form', 'form');
        Blade::component('components.select', 'select');
    }
}
