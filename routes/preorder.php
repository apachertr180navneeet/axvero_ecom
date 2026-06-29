<?php

use App\Http\Controllers\PreorderController;
use App\Http\Controllers\PreorderProductController;
use App\Http\Controllers\PreorderConversationController;
use App\Http\Controllers\PreorderProductReviewController;
use App\Http\Controllers\PreorderProductQueryController;
use App\Http\Controllers\PreorderNotificationTypeController;
/*
|--------------------------------------------------------------------------
| Preorder Routes
|--------------------------------------------------------------------------
*/

//Admin
Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'admin']], function () {
    Route::controller(PreorderController::class)->group(function () {
        Route::get('/preorder/dashboard', 'admin_dashboard')->name('preorder.dashboard');

        // Orders
        Route::get('/preorder/all-orders', 'all_preorder_list')->name('all_preorder.list');
        Route::get('/preorder/inhouse-orders', 'inhouse_preorder_list')->name('inhouse_preorder.list');
        Route::get('/preorder/seller-orders', 'seller_preorder_list')->name('seller_preorder.list');
        Route::get('/preorder/delayed-prepayment', 'delayed_prepayment_preorders_list')->name('delayed_prepayment_preorders.list');
        Route::get('/preorder/delayed-final-orders', 'delayed_final_orders_list')->name('delayed_final_orders.list');
        Route::get('/preorder/order/{id}', 'preorder_order_show')->name('preorder-order.show');

        // Commission History
        Route::get('/preorder/commission-history', 'commission_history')->name('preorder-commission-history');

        // Settings
        Route::get('/preorder/settings', 'settings')->name('preorder-settings');
    });

    Route::controller(PreorderProductController::class)->group(function () {
        Route::get('/preorder-products', 'all_preorder_product_list')->name('preorder-product.index');
        Route::get('/preorder-product/create', 'product_create_admin')->name('preorder-product.create');
        Route::post('/preorder-product/store', 'product_store_admin')->name('preorder-product.store');
        Route::get('/preorder-products/edit/{id}', 'product_edit_admin')->name('preorder-product.edit');
        Route::post('/preorder-products/update/{id}', 'product_update_admin')->name('preorder-product.update');
        Route::get('/preorder-products/destroy/{id}', 'product_destroy_admin')->name('preorder-product.destroy');
    });

    Route::controller(PreorderConversationController::class)->group(function () {
        Route::get('/preorder-conversations', 'admin_index')->name('preorder-conversations.admin_index');
        Route::get('/preorder-conversations/{id}', 'admin_show')->name('preorder-conversations.admin_show');
    });

    Route::controller(PreorderProductQueryController::class)->group(function () {
        Route::get('/preorder-product-queries', 'index')->name('preorder.product_query.index');
        Route::get('/preorder-product-queries/{id}', 'show')->name('preorder.product_query.show');
    });

    Route::controller(PreorderProductReviewController::class)->group(function () {
        Route::get('/preorder-product-reviews', 'index')->name('preorder.product_reviews.index');
    });

    Route::controller(PreorderNotificationTypeController::class)->group(function () {
        Route::get('/preorder-notification-types', 'index')->name('preorder-notification-types.index');
        Route::get('/preorder-notification-types/edit/{id}', 'edit')->name('preorder.notification-type.edit');
    });
});

//Seller
Route::group(['prefix' => 'seller', 'middleware' => ['seller', 'verified', 'user']], function () {
    Route::controller(PreorderController::class)->group(function () {
        Route::get('/preorder/dashboard', 'seller_dashboard')->name('seller.preorder.dashboard');

        // Orders
        Route::get('/preorder/all-orders', 'seller_all_preorder_list')->name('seller.all_preorder.list');
        Route::get('/preorder/delayed-prepayment', 'seller_delayed_prepayment')->name('seller.delayed_prepayment_preorders.list');
        Route::get('/preorder/delayed-final-orders', 'seller_delayed_final')->name('seller.delayed_final_orders.list');
        Route::get('/preorder/order/{id}', 'seller_preorder_order_show')->name('seller.preorder-order.show');

        // Settings
        Route::get('/preorder/settings', 'seller_settings')->name('seller.preorder-settings');

        // Commission History
        Route::get('/preorder/commission-history', 'seller_commission_history')->name('seller.preorder-commission-history');
    });

    Route::controller(PreorderProductController::class)->group(function () {
        Route::get('/preorder-products', 'all_preorder_product_list_seller')->name('seller.preorder-product.index');
        Route::get('/preorder-product/create', 'product_create_seller')->name('seller.preorder-product.create');
        Route::post('/preorder-product/store', 'product_store_seller')->name('seller.preorder-product.store');
        Route::get('/preorder-products/edit/{id}', 'product_edit_seller')->name('seller.preorder-product.edit');
        Route::post('/preorder-products/update/{id}', 'product_update_seller')->name('seller.preorder-product.update');
        Route::get('/preorder-products/destroy/{id}', 'product_destroy_seller')->name('seller.preorder-product.destroy');
    });

    Route::controller(PreorderConversationController::class)->group(function () {
        Route::get('/preorder-conversations', 'seller_index')->name('seller.preorder-conversations.index');
        Route::get('/preorder-conversations/{id}', 'seller_show')->name('seller.preorder-conversations.show');
    });

    Route::controller(PreorderProductQueryController::class)->group(function () {
        Route::get('/preorder-product-queries', 'seller_index')->name('seller.preorder_product_query.index');
        Route::get('/preorder-product-queries/{id}', 'seller_show')->name('seller.preorder_product_query.show');
    });

    Route::controller(PreorderProductReviewController::class)->group(function () {
        Route::get('/preorder-product-reviews', 'seller_index')->name('seller.preorder_product_reviews');
    });
});

//Customer
Route::group(['middleware' => ['auth']], function () {
    Route::controller(PreorderController::class)->group(function () {
        Route::get('/preorder/orders', 'customer_order_list')->name('preorder.order_list');
        Route::get('/preorder/order-details/{id}', 'customer_order_details')->name('preorder.order_details');
    });

    Route::controller(PreorderConversationController::class)->group(function () {
        Route::get('/preorder/conversations', 'customer_index')->name('preorder-conversations.customer-index');
        Route::get('/preorder/conversations/{id}', 'customer_show')->name('preorder-conversations.customer-show');
    });
});

//Public
Route::controller(PreorderProductController::class)->group(function () {
    Route::get('/preorder-product/{slug}', 'product_details')->name('preorder-product.details');
    Route::get('/preorder-products', 'all_preorder_products')->name('preorder-products.all');
});


