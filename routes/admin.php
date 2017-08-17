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

//用户登录
Route::post('auth-login','AuthController@login')->name('api_auth');
//  页面的视图
Route::get('/product/list', function () {
    return view('admin.product.list');
});
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
//登录后操作
Route::group(['middleware' => 'jwt.auth'],function(){
    Route::get('auth-logout','LoginController@logout')->name('logout_admin');
    Route::post('add-admin','UserController@addAdmin')->name('add_admin');
    Route::post('update-admin','UserController@updateAdmin')->name('update_admin');
    Route::get('admin-list/{page}','UserController@adminList')->where('page','[0-9]+')->name('admin-list');
    Route::get('customer-list/{page}','CustomerController@customerList')->where('page','[0-9]+')->name('customer-list');
   // Route::post('culture/add','CultureController@add');
    Route::resource('culture','CultureController');
    Route::resource('product','ProductController');
    Route::resource('agency','AgencyController');
    Route::get('culture/view/{id}','CultureController@view')->where('id','[0-9]+');
    Route::get('area/list/{level?}/{id?}','AreaController@getList')->where(['level'=>'[0-9]+','id'=>'[0-9]+']);
    Route::get('culture-type','CultureController@getTypeList');
});

