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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
// Route::get('/users', 'UserController@index');
// Route::get('/users/{id}', 'UserController@show');
// Route::post('/users', 'UserController@store');
// Route::put('/users/{id}', 'UserController@update');
// Route::delete('/users/{id}', 'UserController@destroy');
// Route::apiResource('/users', 'UserController');

Route::post('/login', 'AuthController@login');
Route::get('login', 'AuthController@login')->name('login');
Route::post('/register', 'AuthController@register');

Route::group(['middleware' => 'auth:api', 'prefix' => 'admin', 'namespace' => 'Admin'], function () {
    Route::post('/logout', 'AuthController@logout');
    Route::get('chart', 'DashboardController@chart');
    Route::get('user', 'UserController@user');
    Route::put('user/info', 'UserController@updateInfo');
    Route::put('user/password', 'UserController@updatePassword');
    Route::post('upload', 'ImageController@upload');
    Route::get('export', 'OrderController@export');
    Route::apiResource('users', 'UserController');
    Route::apiResource('roles', 'RoleController');
    Route::apiResource('products', 'ProductController');
    Route::apiResource('orders', 'OrderController')->only('index', 'show');
    Route::apiResource('permissions', 'PermissionController')->only('index');
});
