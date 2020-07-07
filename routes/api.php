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

Route::middleware('auth:api')->prefix('user')->group(function () {
    Route::get('/me', 'UserController@me');
});

Route::middleware('auth:api')->prefix('product')->group(function () {
    Route::post('store', 'ProductController@store')->name('product.store');
    Route::post('{uuid}/update', 'ProductController@update')->name('product.update');
    Route::post('find', 'ProductController@findProducts')->name('product.find');
    Route::delete('{uuid}/delete', 'ProductController@delete')->name('product.delete');
});

Route::post('login', 'UserController@login');
Route::post('register', 'UserController@store')->name('user.store');

Route::middleware('auth:api')->post('/logout', 'UserController@logout');
