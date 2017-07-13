<?php
/* ---------------------------------------------
*  个人中心模块
* --------------------------------------------- */
//我的商城
Route::get('home/user/index','Home\UserController@index');
//验证手机
Route::get('home/user/validateMobile/{id}',"Home\UserController@validateMobile");
//获取验证手机的验证码
Route::get('home/user/mobileCode/{mobile}',"Home\UserController@mobileCode");
//检验手机验证码
Route::get("home/user/checkMobileCode", "Home\UserController@checkMobileCode");

//验证邮箱
Route::get('home/user/validateEmail/step/{id}', "Home\UserController@validateEmail");
//获取验证邮箱的验证码
Route::get("home/user/emailCode/{step}/{email}", "Home\UserController@emailCode");
//对比邮箱验证码
Route::post("home/user/checkCode/{code}/{email}", "Home\UserController@checkCode");
//修改密码
Route::get("home/user/changePassword", "Home\UserController@changePassword");
//处理修改密码ajax
Route::post("home/user/ajaxPassword", "Home\UserController@ajaxPassword");


//个人信息
Route::get('home/user/userInfo','Home\UserController@userInfo');
//个人信息---上传图片
Route::get("home/uploadify/upload/{num}/{elementid}/{path}/{callback}", "Home\UserController@ajaxPic");
//个人信息---上传图片---图片处理
Route::post("home/uploadify/picDispose", 'Home\UserController@picDispose');
//个人信息---上传图片---删除图片
Route::get("home/uploadify/delUpload", "Home\UserController@delUpload");
//个人信息---上传图片---保存图片到数据库
Route::get("home/uploadify/saveImg", "Home\UserController@saveImg");
//个人信息保存
Route::post("home/user/saveUserInfo", "Home\UserController@saveUserInfo");


//我的订单
//Route::get('home/user/orderList','Home\UserController@orderList');
//我的收藏
Route::get('home/user/goodsCollect','Home\UserController@goodsCollect');
//取消收藏
Route::get('home/user/cancelCollect/{id}','Home\UserController@cancelCollect');
//------------------------------------------------------------------

//公共省市地三级联动路由
//获得城市
Route::get('getCities/pid/{pid}','Home\AddressController@getCities');
//获得地区
Route::get('getAreas/pid/{pid}','Home\AddressController@getAreas');
//获得乡镇街道
Route::get('getTowns/pid/{pid}','Home\AddressController@getTowns');

//设置默认地址
Route::get('home/user/addressSetDefault/{id}','Home\AddressController@addressSetDefault');

Route::resource('home/user/address','Home\AddressController');
//------------------------------------------------------------------

//退换货列表
Route::get('home/user/returnGoods','Home\UserController@returnGoodsList');
//退换货查看
Route::get("home/user/returnGoodsInfo/{id}", "Home\UserController@returnGoodsInfo");
//退换货添加
Route::get('home/user/returnGoodsAdd/gid/{gid}','Home\UserController@returnGoodsAdd');
