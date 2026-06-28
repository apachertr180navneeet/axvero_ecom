<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAffiliateTables extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('affiliate_options')) {
            Schema::create('affiliate_options', function (Blueprint $table) {
                $table->id();
                $table->string('type', 100);
                $table->string('status', 10)->default('0');
                $table->text('details')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('affiliate_configs')) {
            Schema::create('affiliate_configs', function (Blueprint $table) {
                $table->id();
                $table->string('type', 100);
                $table->longText('value')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('affiliate_users')) {
            Schema::create('affiliate_users', function (Blueprint $table) {
                $table->id();
                $table->integer('user_id');
                $table->integer('status')->default(0);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('affiliate_payments')) {
            Schema::create('affiliate_payments', function (Blueprint $table) {
                $table->id();
                $table->integer('affiliate_user_id');
                $table->double('amount', 20, 2)->default(0.00);
                $table->string('payment_method', 100)->nullable();
                $table->longText('payment_details')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('affiliate_withdraw_requests')) {
            Schema::create('affiliate_withdraw_requests', function (Blueprint $table) {
                $table->id();
                $table->integer('user_id');
                $table->double('amount', 20, 2)->default(0.00);
                $table->integer('status')->default(0);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('affiliate_logs')) {
            Schema::create('affiliate_logs', function (Blueprint $table) {
                $table->id();
                $table->integer('user_id');
                $table->integer('order_id')->nullable();
                $table->integer('order_detail_id')->nullable();
                $table->double('referral_amount', 20, 2)->default(0.00);
                $table->integer('log_type')->default(0);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('affiliate_stats')) {
            Schema::create('affiliate_stats', function (Blueprint $table) {
                $table->id();
                $table->integer('user_id');
                $table->integer('no_of_click')->default(0);
                $table->integer('no_of_item_sold')->default(0);
                $table->integer('no_of_delivered')->default(0);
                $table->integer('no_of_canceled')->default(0);
                $table->double('total_amount', 20, 2)->default(0.00);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('affiliate_earning_details')) {
            Schema::create('affiliate_earning_details', function (Blueprint $table) {
                $table->id();
                $table->integer('user_id');
                $table->double('amount', 20, 2)->default(0.00);
                $table->string('type', 100)->nullable();
                $table->longText('details')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('affiliate_options');
        Schema::dropIfExists('affiliate_configs');
        Schema::dropIfExists('affiliate_users');
        Schema::dropIfExists('affiliate_payments');
        Schema::dropIfExists('affiliate_withdraw_requests');
        Schema::dropIfExists('affiliate_logs');
        Schema::dropIfExists('affiliate_stats');
        Schema::dropIfExists('affiliate_earning_details');
    }
}
