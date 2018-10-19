<?php

// use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register Admin routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "admin" middleware group. Enjoy building your Admin!
|
*/

Route::namespace('Admin')->group(function () {
    Route::group(['middleware' => ['auth.admin']],  function () {
        Route::get('/', 'HomeController@index');
    });
});
