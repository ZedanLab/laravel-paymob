<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use ZedanLab\Paymob\Enums\PaymobTransactionStatus;
use ZedanLab\Paymob\Enums\PaymobTransactionType;

return new class extends Migration
{
    public function up()
    {
        Schema::create('paymob_transactions', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('paymob_id');
            $table->string('hmac');
            $table->json('data');

            $table->enum('status', PaymobTransactionStatus::getValues())->default(PaymobTransactionStatus::PENDING)->index();
            $table->enum('type', PaymobTransactionType::getValues())->index();

            $table->nullableMorphs('payable');
            $table->nullableMorphs("payer");

            $table->timestamps();
        });
    }
};
