<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AffiliateController;

Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'admin', 'prevent-back-history']], function () {
    Route::controller(AffiliateController::class)->group(function () {
        Route::get('/affiliate', 'index')->name('affiliate.index');
        Route::post('/affiliate/store', 'affiliate_option_store')->name('affiliate.store');
        Route::get('/affiliate/configs', 'configs')->name('affiliate.configs');
        Route::post('/affiliate/configs/store', 'config_store')->name('affiliate.configs.store');
        Route::get('/affiliate/users', 'users')->name('affiliate.users');
        Route::get('/affiliate/users/show_verification_request/{id}', 'show_verification_request')->name('affiliate_users.show_verification_request');
        Route::get('/affiliate/users/approve/{id}', 'approve_user')->name('affiliate_user.approve');
        Route::get('/affiliate/users/reject/{id}', 'reject_user')->name('affiliate_user.reject');
        Route::post('/affiliate/users/approved', 'updateApproved')->name('affiliate.users.approved');
        Route::post('/affiliate/payment_modal', 'payment_modal')->name('affiliate.payment_modal');
        Route::post('/affiliate/payment_store', 'payment_store')->name('affiliate_user.payment_store');
        Route::get('/affiliate/payment_history/{id}', 'payment_history')->name('affiliate_user.payment_history');
        Route::get('/affiliate/referral_users', 'refferal_users')->name('affiliate.refferal_users');
        Route::get('/affiliate/withdraw_requests', 'affiliate_withdraw_requests')->name('affiliate.withdraw_requests');
        Route::post('/affiliate/withdraw_modal', 'affiliate_withdraw_modal')->name('affiliate_withdraw_modal');
        Route::post('/affiliate/withdraw_request_payment_store', 'withdraw_request_payment_store')->name('withdraw_request.payment_store');
        Route::get('/affiliate/withdraw_request/reject/{id}', 'reject_withdraw_request')->name('affiliate.withdraw_request.reject');
        Route::get('/affiliate/logs', 'affiliate_logs_admin')->name('affiliate.logs_admin');
    });
});
