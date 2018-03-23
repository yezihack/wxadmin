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

Route::get('test')->uses('CommonController@test');
Route::any('login')->uses('LoginController@view')->name('login');//登陆
Route::any('weixin')->uses('WxController@index');//微信处理
//Route::get('db107')->uses('CommonController@db107');


//对外提供的api
Route::group([], function () {
    Route::get('get_access_token')->uses('CommonController@getAccessToken');
    Route::get('notice')->uses('TemplateController@notice');
    Route::get('user_list')->uses('CommonController@user_list');
    Route::get('auth')->uses('WxController@auth');
    Route::get('openid')->uses('WxController@openid')->name('openid');
});

//主页路由
Route::group(['middleware' => 'check.login'], function () {
    Route::get('c')->uses('CommonController@c')->name('clear');
    Route::get('/')->uses('CommonController@index')->name('home');
    Route::get('logout')->uses('LoginController@logout')->name('logout');//登出
    Route::get('menu')->uses('CommonController@menu')->name('menu');//操作菜单
    Route::get('welcome')->uses('CommonController@welcome')->name('welcome');//欢迎页
    Route::any('upload')->uses('CommonController@upload')->name('upload');//上传文件
});

//用户路由
Route::group(['middleware' => 'check.login', 'prefix' => 'user', 'as' => 'user.'], function () {
    Route::any('list')->uses('UserController@lists')->name('list');//列表
    Route::any('dit')->uses('UserController@edit')->name('edit');//编辑
    Route::any('add')->uses('UserController@add')->name('add');//添加
    Route::post('del')->uses('UserController@del')->name('del');//删除
    Route::any('pass')->uses('UserController@pass')->name('pass');//修改密码
    Route::post('change')->uses('UserController@change')->name('change');//切换
});
//菜单路由
Route::group(['middleware' => 'check.login', 'prefix' => 'menu', 'as' => 'menu.'], function () {
    Route::any('list')->uses('MenuController@lists')->name('list');//列表
    Route::any('edit')->uses('MenuController@edit')->name('edit');//编辑
    Route::any('add')->uses('MenuController@add')->name('add');//添加
    Route::post('del')->uses('MenuController@del')->name('del');//删除
    Route::post('change')->uses('MenuController@change')->name('change');//删除
    Route::get('sync')->uses('MenuController@sync')->name('sync');
    Route::get('clear')->uses('MenuController@clear')->name('clear');
    Route::get('query')->uses('MenuController@query');
    Route::post('weight')->uses('MenuController@weight')->name('weight');
});
//消息路由
Route::group(['middleware' => 'check.login', 'prefix' => 'msg', 'as' => 'msg.'], function () {
    Route::any('list')->uses('MsgController@lists')->name('list');//列表
    Route::any('edit')->uses('MsgController@edit')->name('edit');//编辑
    Route::any('add')->uses('MsgController@add')->name('add');//添加
    Route::post('del')->uses('MsgController@del')->name('del');//删除
    Route::post('change')->uses('MsgController@change')->name('change');//删除
});
//事件路由
Route::group(['middleware' => 'check.login', 'prefix' => 'event', 'as' => 'event.'], function () {
    Route::any('list')->uses('EventController@lists')->name('list');//列表
    Route::any('edit')->uses('EventController@edit')->name('edit');//编辑
    Route::any('add')->uses('EventController@add')->name('add');//添加
    Route::post('del')->uses('EventController@del')->name('del');//删除
    Route::post('change')->uses('EventController@change')->name('change');//更改
});
//二维码路由
Route::group(['middleware' => 'check.login', 'prefix' => 'qr', 'as' => 'qr.'], function () {
    Route::any('list')->uses('QrController@lists')->name('list');//列表
    Route::any('edit')->uses('QrController@edit')->name('edit');//编辑
    Route::any('add')->uses('QrController@add')->name('add');//添加
    Route::post('del')->uses('QrController@del')->name('del');//删除
    Route::post('change')->uses('QrController@change')->name('change');//切换
    Route::get('src')->uses('QrController@src')->name('src');//查看图片
    Route::post('update')->uses('QrController@update')->name('update');//更新字段
    Route::any('stat')->uses('QrController@stat')->name('stat');//统计
    Route::get('download')->uses('QrController@download')->name('download');//下载统计
    Route::get('downqr')->uses('QrController@downqr')->name('downqr');
});