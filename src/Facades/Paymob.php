<?php

namespace ZedanLab\Paymob\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \ZedanLab\Paymob\Paymob
 */
class Paymob extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'laravel-paymob';
    }
}
