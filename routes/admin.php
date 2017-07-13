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

//后台登陆页
Route::any('admin/login', 'Admin\LoginController@login');
Route::get('admin/code', 'Admin\LoginController@code');

Route::group(['middleware' => ['admin.login'], 'prefix' => 'admin'], function () {
    //后台首页
//    Route::get('/', 'Admin\IndexController@index');
//    Route::any('/welcome', function () {
////        return view('Admin.Index.welcome');
//    });
    //修改密码
    Route::any('/pass', 'Admin\IndexController@pass');
    //安全退出
    Route::get('/quit', 'Admin\LoginController@quit');

    Route::any('/User/index', 'Admin\UserController@index');
    
});
