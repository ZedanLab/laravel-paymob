<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use ZedanLab\Paymob\Enums\PaymobPayoutStatus;
use ZedanLab\Paymob\Enums\PaymobPayoutIssuer;

return new class extends Migration
{
    public function up()
    {
        Schema::create('paymob_payouts_transactions', function (Blueprint $table) {
            $table->id();

            $table->string('transaction_id')->unique();
            $table->decimal('amount', 15, 2);
            $table->enum('issuer', PaymobPayoutIssuer::getValues())->index();
            $table->enum('status', PaymobPayoutStatus::getValues())->default(PaymobPayoutStatus::PENDING)->index();
            $table->json('data');
            $table->json('callback')->nullable();
            $table->nullableMorphs("receiver");

            $table->timestamps();
        });
    }
};
