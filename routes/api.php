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

// Login, Register & Logout functions
Route::post('login', 'API\UserController@login')->name('api.login');
Route::post('register', 'API\UserController@register')->name('api.register');
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::middleware('auth:api')->post('details', 'API\UserController@details')->name('api.details');
Route::middleware('auth:api')->post('logout', 'API\UserController@logout')->name('api.logout');

// Google Login Controller
Route::group(['middleware' => ['web']], function () {
	// your routes here
	Route::get('/redirect', 'API\UserController@redirectToGoogle');
	Route::get('/callback', 'API\UserController@handleGoogleCallback');
});


Route::middleware('auth:api')->resource('/sensor', 'SensorController');
Route::middleware('auth:api')->resource('/pet', 'PetController');
Route::middleware('auth:api')->resource('/log', 'SensorLogController');