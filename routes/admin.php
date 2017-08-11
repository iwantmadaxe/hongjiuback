<?php

Route::group(['middleware' => 'auth:admin'], function () {

	Route::get('/notify', 'UserController@notify');

	//用户管理
	Route::get('/users', 'UserController@getUsers');
	Route::put('/user/{id}/enable/{enable}', 'UserController@enable')->where('id', '[0-9]+');    //todo 参数验证
	Route::delete('/user/{id}', 'UserController@delete')->where('id', '[0-9]+');    //todo 参数验证
	Route::get('/user/state', 'UserController@state');
	Route::get('/user/increment', 'UserController@increment');

	//卡管理
	Route::get('/cards', 'CardController@getCards');
	Route::post('/cards', 'CardController@import');
	Route::post('/cards/agent', 'CardController@importAgent');
	Route::post('/card', 'CardController@create');
	Route::get('/card/status/all', 'CardController@status');
	Route::get('/card/operate/{operate}', 'CardController@operate');
	Route::get('/card/status/update', 'CardController@updateStatus');
	Route::get('/card/production/info', 'CardController@productionInfo');
	Route::post('/card/net/add', 'CardController@addNet');
	Route::post('/card/net/update', 'CardController@updateNet');
	Route::post('/card/net/delete', 'CardController@deleteNet');
	Route::post('/card/net/recover', 'CardController@recoverNet');
	Route::get('/card/flow/detail', 'CardController@flowDetail');
	Route::get('/card/flow/update', 'CardController@updateFlow');

	//运营商管理
	Route::get('/operators', 'OperatorController@index');
	Route::get('/operator/list', 'OperatorController@getList');
	Route::post('/operator', 'OperatorController@create');
	Route::get('/operator/{id}', 'OperatorController@show');
	Route::put('/operator/{id}', 'OperatorController@update');

	//实名认证
	Route::get('/certificates', 'CertificationController@index');
	Route::get('/certificate/{id}', 'CertificationController@show');
	Route::put('/certificate/{id}', 'CertificationController@update');

	//代理商
	Route::get('/agent/list', 'AgentController@getList');
	Route::get('/agents', 'AgentController@index');
	Route::post('/agent', 'AgentController@create');
	Route::put('/agent/{id}', 'AgentController@update')->where('id', '[0-9]+');
	Route::get('/agent/{id}', 'AgentController@show')->where('id', '[0-9]+');
	Route::put('/agent/cancel', 'AgentController@cancel');
	Route::post('/agent/wechat/menu', 'AgentController@wechatMenu');
	Route::post('/agent/{id}/agent', 'AgentController@addAgent')->where('id', '[0-9]+');    //为代理商添加子代理商

	//套餐
	Route::get('/package/list', 'PackageController@getList');
	Route::get('/packages', 'PackageController@index');
	Route::post('/package', 'PackageController@create');
	Route::put('/package/enable/{enable}', 'PackageController@enable');    //todo 参数验证
	Route::delete('/package', 'PackageController@delete');    //todo 参数验证

	//套餐折扣
	Route::get('/package/discounts', 'PackageController@discountList');
	Route::post('/package/discount', 'PackageController@createDiscount');
	Route::get('/package/discount/{id}', 'PackageController@showDiscount');
	Route::put('/package/discount/{id}', 'PackageController@updateDiscount');
	Route::delete('/package/discount/{id}', 'PackageController@deleteDiscount');
	Route::put('/package/discounts', 'PackageController@updateDiscounts');

	//订单
	Route::get('/orders', 'OrderController@index');
	Route::get('/orders/back', 'OrderController@back');
	Route::get('/percentage', 'OrderController@percentage');
	Route::post('/order/package/type', 'OrderController@setPackageType');

	//管理员
	Route::get('/admins', 'AdminController@index');
	Route::get('/admin/{id}', 'AdminController@show');
	Route::delete('/admin', 'AdminController@delete');
	Route::put('/admin/enable/{bool}', 'AdminController@enable');
	Route::post('/admin', 'AdminController@create');
	Route::post('/update/{id}', 'AdminController@update');

	//代理商充值
	Route::get('/money', 'MoneyController@index');
	Route::put('/agent/{id}/money', 'MoneyController@add');
	Route::get('/agent/money/record', 'MoneyController@record');

	//角色
	Route::get('/role/list', 'RoleController@getList');
	Route::get('/roles', 'RoleController@index');
	Route::post('/role/create', 'RoleController@store');
	Route::get('/role/{id}/edit', 'RoleController@edit')->where('id', '[0-9]+');
	Route::post('/role/{id}', 'RoleController@update')->where('id', '[0-9]+');
    Route::post('/permission/role/{id}', 'RoleController@permission')->where('id', '[0-9]+');

	//文件上传
	Route::post('/file', 'FileController@upload');

	//积分
    Route::get('/points', 'PointController@index');
    Route::get('/points/sponsor', 'PointController@sponsor');
    Route::get('/points/receiver', 'PointController@receiver');
    Route::get('/points/exchange/list', 'PointController@exchangeApplyList');
    Route::post('/points/exchange/{id}', 'PointController@exchangeApply')->where('id', '[0-9]+');
    Route::get('/points/exchange/rate', 'PointController@pointExchangeRateList');
    Route::post('/points/exchange/rate', 'PointController@pointExchangeRate');
    Route::get('/points/package/list', 'PointController@pointPackageList');

	//权限
    Route::get('/permission/list', 'PermissionController@index');
    Route::get('/permission/role/{id}', 'PermissionController@permissionOfRole')->where('id', '[0-9]+');
    Route::get('/permission/my', 'PermissionController@myPermission');

});