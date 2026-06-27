<?php

/*
|--------------------------------------------------------------------------
| Shiprocket Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\Controllers\ShiprocketController;

//Admin
Route::group(['prefix' =>'admin', 'middleware' => ['auth', 'admin']], function(){
    Route::controller(ShiprocketController::class)->group(function () {
        Route::post('/shiprocket-settings-update', 'update')->name('shiprocket_settings.update');
        Route::post('/orders/confirm-shiprocket-info', 'createOrderShiprocket')->name('orders.confirm_shiprocket_info');
        Route::get('/orders/{id}/create-shipment', 'createShipmentFromList')->name('orders.create-shipment');
        Route::get('/shiprocket/preview-payload/{order}', 'previewPayload')->name('shiprocket.preview_payload');
        Route::get('/shiprocket/pickup-locations', 'listPickupLocations')->name('shiprocket.pickup_locations');
        Route::get('/shiprocket/token', 'getToken')->name('shiprocket.token');
        // for cron job of shiprocket delivery status
        Route::get('/shiprocket/delivery-status', 'deliveryStatus')->name('shiprocket.delivery-status');
    });
});    