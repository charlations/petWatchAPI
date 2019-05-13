<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('login', 'API\UserController@login')->name('api.login');
Route::post('register', 'API\UserController@register')->name('api.register');
Route::middleware('auth:api')->post('details', 'API\UserController@details')->name('api.details');
Route::middleware('auth:api')->resource('/sensor', 'SensorController');
Route::middleware('auth:api')->resource('/pet', 'PetController');
Route::middleware('auth:api')->resource('/log', 'SensorLogController');
