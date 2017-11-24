<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', sprintf('%s@index', \SpendTracker\Http\Controllers\RootController::class));
Route::get('import', sprintf('%s@index', \SpendTracker\Http\Controllers\ImportController::class));
Route::get('import/{card}', sprintf('%s@getImport', \SpendTracker\Http\Controllers\ImportController::class));
Route::post('import/{card}', sprintf('%s@postImport', \SpendTracker\Http\Controllers\ImportController::class));
