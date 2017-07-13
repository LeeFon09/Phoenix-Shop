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

//头部搜索
Route::post("Home/Goods/search", "Home\HeaderController@search");


/* ---------------------------------------------
 * 商品浏览模块
 * ---------------------------------------------- */
//商品列表页
Route::get('home/goods/goodslist/{id}','Home\GoodsListController@goodsList');

//商品详情页
Route::get("home/goods/goodsinfo/{id}", "Home\GoodsDetailController@index");

Route::get("home/goods/comment/{id}", "Home\GoodsDetailController@userComment");

Route::post("home/goods/goodsinfo/getPrice", "Home\GoodsDetailController@getPrice");
//列表页添加到购物车
Route::get("home/goods/goodsCart/{goodsid}","Home\GoodsListController@goodsCartAdd");


/*---------------------------------------------------
 * 购物车ajax
 * --------------------------------------------------*/
//ajax购买和加入购物车
Route::post("buy", "Home\AjaxCarController@goodsBuy");

//点击后退按钮，ajax进行的处理
Route::post("backer", "Home\AjaxCarController@back");

//在详情页点击加入购物车后的弹窗
Route::get("larer/shopcar", "Home\AjaxCarController@shopcardLayer");

//公共页头header中购物车的ajax获取数据
Route::get('ajax/header/cart', "Home\AjaxCarController@ajaxHeaderCart");
Route::get("ajax/header/del", "Home\AjaxCarController@ajaxDelCart");

/* ---------------------------------------------
 *  购物车模块
 * --------------------------------------------- */
//查看购物车
Route::get('home/cart/cart', 'Home\CartController@showCart');
//ajax 请求获取购物车列表
Route::post("home/cart/ajaxList", 'Home\CartController@ajaxList');
//ajax 请求获取session，改变sesssion中对应id的num
Route::post("home/cart/ajaxSession", "Home\AjaxCarController@getSession");
//ajax 请求获取session，删除session中对应id的那一项
Route::post("home/cart/ajaxDel", "Home\AjaxCarController@delSession");
//ajax 请求多选框，存入session
Route::get("home/cart/ajaxSelAll", "Home\AjaxCarController@selAll");
//ajax 删除选中的商品
Route::get("home/cart/delMore", "Home\AjaxCarController@delMore");
//ajax 填写购物第二步--核对账单 --收货人信息
Route::any("home/cart/cneeInformation", "Home\AjaxCarController@cneeInformation");






/* ---------------------------------------------
 *  页脚文章内容模块
 * --------------------------------------------- */
Route::get('home/article/detail/{id}','Home\HeaderController@showArticle');


/* ---------------------------------------------
 *  用户登陆注册模块
 * --------------------------------------------- */
//用户登录页,此方法get和post同时使用
Route::any('login', 'Home\LoginController@login');
//用户退出登陆
Route::get('home/user/logout','Home\LoginController@logout');
Route::get('code', 'Home\LoginController@code');

//用户注册页
Route::any('reg', 'Home\RegController@reg');
Route::post('checkUsername', 'Home\RegController@checkUsername');
Route::post('emailCode', 'Home\RegController@emailCode');
Route::get('sendEmail', 'Home\RegController@sendEmail');
Route::post('mobileCode', 'Home\RegController@getMsg');


Route::group(["middleware" => "home.login"], function(){

    //确认订单页面
    Route::post('home/cart/checkOrder', 'Home\CartController@checkOrder');
    //提交订单
    Route::post('home/cart/commitOrder', 'Home\CartController@doCommitOrder');
    //确认支付方式页面
    Route::get('home/cart/checkPayment/{oid}', 'Home\CartController@checkPayment');
    //列表页加入收藏按钮
    Route::get('home/goods/goodsCollect/{goodsid}','Home\GoodsListController@goodsCollectAdd');
    include('home.order.php');
    include('home.user.php');
});






