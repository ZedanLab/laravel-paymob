<?php

namespace ZedanLab\Paymob\Tests\App\Providers;

use ZedanLab\Paymob\Paymob;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
    }

    public function boot()
    {
        Paymob::routes();
    }
}
