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


//首页热门商品
Route::any('/', "Home\IndexController@banner");

//首页最顶部大块广告   描述：首页顶部的广告位刚打开时弹出来过一会自动缩回去.
Route::get("/ad", "Home\AdController@ad");

Route::get("hot", "Home\IndexController@shopChild");





//后台登陆页
Route::any('admin/login', 'Admin\LoginController@login');
Route::get('admin/code', 'Admin\LoginController@code');

Route::group(['middleware' => ['admin.login'], 'prefix' => 'admin'], function () {
    //后台首页
    Route::get('/', 'Admin\IndexController@index');
    Route::get('/welcome', 'Admin\IndexController@welcome');
    //修改密码
    Route::any('/pass', 'Admin\IndexController@pass');
    //安全退出
    Route::get('/quit', 'Admin\LoginController@quit');

    Route::any('/User/index', 'Admin\UserController@index');


    include('admin.user.php');

    include('admin.goods.php');

    include('admin.order.php');

    include('admin.article.php');

    include('admin.admin.php');

    include('admin.role.php');

    include('admin.access.php');

    include('admin.comment.php');

    include('admin.ad.php');
});



