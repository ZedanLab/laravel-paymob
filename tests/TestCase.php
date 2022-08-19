<?php

namespace ZedanLab\Paymob\Tests;

use Illuminate\Foundation\Bootstrap\LoadEnvironmentVariables;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Orchestra\Testbench\TestCase as Orchestra;
use ZedanLab\Paymob\PaymobServiceProvider;
use ZedanLab\Paymob\Tests\App\Providers\AppServiceProvider;

class TestCase extends Orchestra
{
    use WithFaker;
    use RefreshDatabase;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__ . '/database/migrations/create_paymob_transactions_table.php');
    }

    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            AppServiceProvider::class,
            PaymobServiceProvider::class,
        ];
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $envPath = __DIR__ . '/App//';

        if (! file_exists($envPath . '.env')) {
            copy($envPath . '.env.example', $envPath . '.env');
        }

        $app->useEnvironmentPath($envPath);

        $app->bootstrapWith([LoadEnvironmentVariables::class]);
        parent::getEnvironmentSetUp($app);
    }
}
