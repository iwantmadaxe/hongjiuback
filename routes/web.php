<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', function () {
	return view('admin.product.list');
})->middleware('auth:admin');

Route::get('/login', function () {
	return view('admin.login.index');
})->middleware('guest:admin');

Route::group(['middleware' => 'auth:admin'], function () {
    // Route::get('/user/user', function () {    //  用户管理 用户列表
    //     return view('admin.user.user');
    // })->middleware(['permission:user_watch']);
    Route::get('/home', 'Admin\HomeController@index');
});

Route::post('/login', 'Admin\AuthController@login')->name('login');
Route::get('/logout', 'Admin\AuthController@logout')->name('logout');
