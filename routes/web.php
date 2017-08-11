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

    Route::get('/product/list', function () {
        return view('admin.product.list');
    });

    Route::get('/product/add', function () {
        return view('admin.product.add');
    });

    Route::get('/product/edit', function () {
        return view('admin.product.edit');
    });

    Route::get('/merchant/list', function () {
        return view('admin.merchant.list');
    });

    Route::get('/merchant/add', function () {
        return view('admin.merchant.add');
    });

    Route::get('/merchant/edit', function () {
        return view('admin.merchant.edit');
    });

    Route::get('/banner/list', function () {
        return view('admin.banner.list');
    });

    Route::get('/banner/add', function () {
        return view('admin.banner.add');
    });

    Route::get('/banner/edit', function () {
        return view('admin.banner.edit');
    });

    Route::get('/context/list', function () {
        return view('admin.context.list');
    });

    Route::get('/context/add', function () {
        return view('admin.context.add');
    });

    Route::get('/context/edit', function () {
        return view('admin.context.edit');
    });

    Route::get('/activity/list', function () {
        return view('admin.activity.list');
    });

    Route::get('/activity/add', function () {
        return view('admin.activity.add');
    });

    Route::get('/activity/edit', function () {
        return view('admin.activity.edit');
    });

    Route::get('/history/list', function () {
        return view('admin.history.list');
    });

    Route::get('/history/add', function () {
        return view('admin.history.add');
    });

    Route::get('/history/edit', function () {
        return view('admin.history.edit');
    });

    Route::get('/msg/list', function () {
        return view('admin.msg.list');
    });

    Route::get('/msg/add', function () {
        return view('admin.msg.add');
    });

    Route::get('/msg/edit', function () {
        return view('admin.msg.edit');
    });

    Route::get('/man/list', function () {
        return view('admin.man.list');
    });

    Route::get('/man/add', function () {
        return view('admin.man.add');
    });

    Route::get('/man/edit', function () {
        return view('admin.man.edit');
    });


    Route::get('/home', 'Admin\HomeController@index');
});

Route::post('/login', 'Admin\AuthController@login')->name('login');
Route::get('/logout', 'Admin\AuthController@logout')->name('logout');
