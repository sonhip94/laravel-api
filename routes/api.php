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
Route::group(['namespace' => 'Api'], function () {
    Route::post('user/register', 'UsersController@register');
    Route::post('user/login', 'UsersController@login');
    Route::get('user/refresh', 'UsersController@refresh');
    Route::post('user/logout', 'UsersController@logout');
    
    Route::group(['middleware' => ['api', 'jwt.auth']], function () {
        Route::put('user/me', 'UsersController@updateProfile');
        Route::get('user/me', 'UsersController@getCurrentUser');
        Route::resource('products', 'ProductsController');
    });
});