<?php

use Illuminate\Http\Request;
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

Route::prefix('user')->group(function () {
    Route::post('/store', 'UserController@store');
    Route::middleware('auth:api')->group(function () {
        Route::get('/me', 'UserController@me');
    });
});

Route::middleware('auth:api')->prefix('product')->group(function () {
    Route::post('store', 'ProductController@store');
    Route::post('{uuid}/update', 'ProductController@update');
    Route::post('find', 'ProductController@findProducts');
    Route::delete('{uuid}/delete', 'ProductController@delete');
});

Route::post('/login', 'UserController@login');
Route::middleware('auth:api')->post('/logout', 'UserController@logout');
