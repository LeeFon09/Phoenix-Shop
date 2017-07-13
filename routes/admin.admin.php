<?php

//后台管理员路由
Route::resource('Admin/index', 'Admin\AdminController');

//根据关键字搜索管理员
Route::post('Admin/research', 'Admin\AdminController@research');

//更新管理员信息
Route::post('Admin/update', 'Admin\AdminController@update');

//删除管理员
Route::get('Admin/del/{admin_id}', 'Admin\AdminController@del');


