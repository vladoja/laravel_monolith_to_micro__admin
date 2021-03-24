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

// Common routes
Route::post('/login', 'AuthController@login');
Route::get('login', 'AuthController@login')->name('login');
Route::post('/register', 'AuthController@register');

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('user', 'AuthController@user');
    // Pozor. V orig. kurze to je users/info
    Route::put('user/info', 'AuthController@updateInfo');
    Route::put('user/password', 'AuthController@updatePassword');
});

// Admin routes
Route::group(['middleware' => ['auth:api', 'scope:admin'], 'prefix' => 'admin', 'namespace' => 'Admin'], function () {
    Route::post('/logout', 'AuthController@logout');
    Route::get('chart', 'DashboardController@chart');
    Route::post('upload', 'ImageController@upload');
    Route::get('export', 'OrderController@export');
    Route::apiResource('users', 'UserController');
    Route::apiResource('roles', 'RoleController');
    Route::apiResource('products', 'ProductController');
    Route::apiResource('orders', 'OrderController')->only('index', 'show');
    Route::apiResource('permissions', 'PermissionController')->only('index');
});

// Influencer routes
Route::group(['prefix' => 'influencer', 'namespace' => 'Influencer'], function () {
    Route::get('products', 'ProductController@index');

    Route::group(['middleware' => ['auth:api', 'scope:influencer']], function () {
        Route::post('links', 'LinkController@store');
    });
});


// Checkout routes
Route::group(['prefix' => 'checkout', 'namespace' => 'Checkout'], function () {
    Route::get('links/{code}', 'LinkController@show');
    Route::post('orders', 'OrderController@store');
});
