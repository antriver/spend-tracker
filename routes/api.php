<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::resource('merchants', \SpendTracker\Http\Controllers\Api\MerchantsController::class);

Route::get('/', function (Request $request) {
    echo 'hello world';
});
