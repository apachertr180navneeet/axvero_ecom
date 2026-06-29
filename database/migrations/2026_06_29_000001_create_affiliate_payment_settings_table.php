<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('affiliate_payment_settings')) {
            Schema::create('affiliate_payment_settings', function (Blueprint $table) {
                $table->id();
                $table->integer('user_id')->unique();
                $table->string('bank_name')->nullable();
                $table->string('bank_acc_name')->nullable();
                $table->string('bank_acc_no')->nullable();
                $table->string('bank_iban')->nullable();
                $table->string('bank_routing_no')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('affiliate_payment_settings');
    }
};
