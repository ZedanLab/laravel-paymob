<?php

namespace ZedanLab\Paymob;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class PaymobServiceProvider extends PackageServiceProvider
{
    /**
     * @param Package $package
     */
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-paymob')
            ->hasMigrations('create_paymob_transactions_table')
            ->hasConfigFile();
    }

    public function packageRegistered()
    {
        $config = $this->app->make('config')->get('paymob');

        $this->app->bind('laravel-paymob', fn () => new Paymob($config));
    }
}
