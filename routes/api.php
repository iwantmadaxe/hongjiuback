<?php

Route::get('/sms', 'SmsController@send');    //发送短信

Route::get('/test', 'ApiTestController@index');

//用户注册登录各种
Route::post('/user', 'AuthController@register');
Route::post('/user/login', 'AuthController@login');
Route::put('/user/password/forget', 'AuthController@resetPassword');

//Route::get('/card/import', 'CardController@import');

Route::group(['middleware' => 'token.auth'], function () {
    Route::get('/user/logout', 'AuthController@logout');    //退出登录

	Route::put('/user', 'UserController@update');
	Route::put('/user/password', 'UserController@updatePassword');
	Route::put('/user/phone', 'UserController@updatePhone');
	Route::put('/user/phone/bind', 'UserController@bindPhone');
	Route::get('/user/profile', 'UserController@profile');
	Route::get('/user/messages', 'UserController@messages');
	Route::put('/user/message/read', 'UserController@readMessage');

	// 认证
	Route::post('/user/certificate', 'UserController@certificate'); // 提交认证
	Route::get('/user/certificates', 'UserController@certificates'); // 认证列表

    // 卡套餐和流量
	Route::get('/package/{packageId}/card/{cardId}/order', 'PackageController@order');
	Route::get('/package/record', 'PackageController@record');
	Route::get('/flow/balance', 'PackageController@balance');
	Route::get('/flow/packages', 'PackageController@packageList');    // 套餐列表

    // 卡列表
	Route::get('/user/cards', 'UserController@cards');
	Route::post('/card/{id}/nick_name', 'UserController@changeName')->where('id', '[0-9]+');

    // 收货地址
    Route::get('/delivery/address', 'AddressController@index');
    Route::post('/delivery/address/create', 'AddressController@create');
    Route::get('/delivery/address/default', 'AddressController@getDefault');
    Route::get('/delivery/address/{id}/edit', 'AddressController@edit')->where('id', '[0-9]+');
    Route::post('/delivery/address/{id}/update', 'AddressController@update')->where('id', '[0-9]+');
    Route::post('/delivery/address/{id}/delete', 'AddressController@delete')->where('id', '[0-9]+');
    Route::post('/delivery/address/{id}/default', 'AddressController@setDefault')->where('id', '[0-9]+');

    // 购买卡
    Route::get('/card/packages', 'PackageController@buyCardList');
    Route::post('/card/packages/{packageId}', 'PackageController@buyCardOrder')->where('packageId', '[0-9]+');

     // 产生二维码
    Route::get('/user/qrcode', 'UserController@generateQrCode');

    // 积分记录
    Route::get('/points', 'PointController@index');
    Route::get('/points/drawing', 'PointController@money');
    Route::get('/points/drawing/rate', 'PointController@drawMoney');
    Route::post('/points/drawing/apply', 'PointController@applyMoney');
    Route::get('/points/drawing/rule', 'PointController@drawMoneyRule');
    Route::get('/points/packages', 'PointController@pointPackages');
    Route::post('/points/packages/apply', 'PointController@pointPackageApply');
});


//一些辅助的接口
Route::get('/option/area/{code}', 'OptionController@area');

Route::post('/notify_url/{wechat}', 'WechatController@notify');