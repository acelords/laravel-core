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

Route::get('/redis/fetch', 'RedisController@fetch');

/**
 * system settings
 */
Route::get('/settings', 'ConfigController@settings')->name('system.settings');
