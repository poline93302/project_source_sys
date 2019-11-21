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
    return redirect()->to(route('former_homepage'));
});

//登入後畫面
Route::group(['prefix' => 'monitor'], function () {
//  基本監控畫面
    Route::get('/', 'FormerPlaceController@show')->name('monitor_homepage');
//  查詢監控畫面
    Route::post('/select', 'FormerPlaceController@select')->name('monitor_homepage_select');
//  更新農夫設定資料
    Route::get('/update', 'FormerPlaceController@stepClassification')->name('monitor_former_update');
//  點選id轉化到監控畫面
    Route::get('/{farm}/{farmland}', 'FormerConfigController@show')->name('monitor_former_config');
});

//有關使用者相關
Route::group(['prefix' => 'user'], function () {
    Route::get('/', 'FormerInfoController@index')->name('former_homepage');
    Route::post('/', 'FormerInfoController@login')->name('former_login');
    Route::post('/logout', 'FormerInfoController@logout')->name('former_logout');
    Route::get('/singUp','FormerInfoController@singUp')->name('former_singUp');
    Route::post('/register', 'FormerInfoController@register')->name('former_register');
});


