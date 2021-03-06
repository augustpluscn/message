<?php

use Illuminate\Support\Facades\Route;

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

Route::namespace ('App\Http\Controllers\Api')->middleware(['check.api.auth'])->group(function () {
    //数据字典
    Route::any('dd', 'DdController@getDd');
});

Route::namespace ('App\Http\Controllers\Api')->group(function () {
    //数据字典
    Route::any('sendmsg', 'MessageController@sendMsg');
    Route::any('sendcard', 'MessageController@sendCard');
});
