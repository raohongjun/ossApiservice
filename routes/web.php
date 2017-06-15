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

Route::get('/', 'home\IndexController@index');

//多个中间件CheckFile 验证
Route::group(['prefix' => 'index', 'namespace' => 'home', 'middleware' => 'checkfile'], function () {
    //别名路由
    Route::post('upload', 'IndexController@upload')->name('upload');
});