<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//根据wine_id返回酒的详细介绍资料
Route::get('/product', 'ProductController@index');
Route::get('/product/filter','ProductController@wineList');//排序列表//通过关键字或者barcode
Route::get('/test/show/{id}', 'TestPrjController@show')->where('id', '[0-9]+');

Route::get('/agency/detail/{id}','AgencyController@getDetail')->where('id','[0-9]+');
Route::get('/agency/by-city/{id}','AgencyController@getAgenciesByCity')->where('id','[0-9]+');
Route::get('/agency/covered','AgencyController@getCoverCities');

Route::get('/culture/list/{culture_type_id?}/{page?}','CultureController@getList')->where(['culture_type_id' => '[0-9]+', 'page' => '[0-9]+']);
Route::get('/culture/view/{id}','CultureController@view')->where('id','[0-9]+');

Route::get('/activity/list/{page?}','ActivityController@getList')->where([ 'page' => '[0-9]+']);

Route::post('/customer/buy','CustomerController@add');
Route::post('/agency-apply/add','AgencyApplyController@add');

Route::get('/agency-district/list/{level?}/{id?}','AgencyDistrictController@getList')->where(['level'=>'[0-9]+','id'=>'[0-9]+']);
Route::get('/agency/by-geo/{zoom}/{center_lat}/{center_lng}/{delta_lat}/{delta_lng}','AgencyController@agencyByGeo');
Route::get('/agency-apply/send-code/{phone}','AgencyApplyController@sendCode');
Route::get('/area/get','AreaController@getAreas');