<?php

namespace ZedanLab\Paymob;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class PaymobServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-paymob')
            ->hasRoute('web')
            ->hasMigrations('create_paymob_transactions_table')
            ->hasConfigFile();
    }
}
