<?php

namespace ZedanLab\Paymob\Tests\App\Providers;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\ServiceProvider;
use ZedanLab\Paymob\Paymob;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Artisan::call('key:generate');
        Paymob::routes();
    }
}
