<?php

use Illuminate\Support\Facades\Route;
use ZedanLab\Paymob\Http\Controllers\PaymobCallbackController;

Route::controller(PaymobCallbackController::class)->as('paymob.callbacks.')->group(function () {
    Route::post(config('paymob.callbacks.transaction_processed_route'), 'transactionProcessed')->name('transactionProcessed');
    Route::get(config('paymob.callbacks.transaction_response_route'), 'transactionResponse')->name('transactionResponse');
});
