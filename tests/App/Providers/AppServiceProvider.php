<?php

namespace ZedanLab\Paymob\Tests\App\Providers;

use Illuminate\Support\ServiceProvider;
use ZedanLab\Paymob\Paymob;

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
