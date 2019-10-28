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

Route::group(['prefix' => 'numerical'], function () {
    Route::post('/target', 'apiController@numberTarget')->name('api.get.number.target');
    Route::post('/findNow', 'apiController@getNowData')->name('api.get.item.number');
    Route::post('/critical', 'apiController@sensorChangeData')->name('api.get.item.critical');
    Route::get('/test', 'apiController@getNowData');
});

Route::group(['prefix' => 'config'], function () {
    Route::post('/create', 'FormerConfigController@create')->name('api.post.config.create');
    Route::post('/update', 'FormerConfigController@update')->name('api.post.config.update');
    Route::post('/switch', 'FormerConfigController@switch')->name('api.post.config.switch');
    Route::post('/delete', 'FormerConfigController@delete')->name('api.post.config.delete');
    Route::post('/updateWeightsThreshold', 'FormerConfigController@updateWeightsThreshold')->name('api.post.weight.threshold.update');
});

Route::group(['prefix' => 'item'], function () {
    Route::post('/history', 'apiController@sensorHistoryBy')->name('api.post.sensor.history');
});




