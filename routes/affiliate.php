<?php

/*
|--------------------------------------------------------------------------
| Affiliate Routes
|--------------------------------------------------------------------------
|
| Routes for the custom affiliate program.
|
*/

//Admin
Route::group(['prefix' =>'admin', 'middleware' => ['auth', 'admin']], function(){
});

//FrontEnd
Route::group(['middleware' => ['auth']], function(){
});
