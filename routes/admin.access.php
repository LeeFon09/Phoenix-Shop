<?php

//角色权限路由
Route::resource('Admin/access', 'Admin\AccessController');

//删除角色权限
Route::get('Admin/access/del/{id}', 'Admin\AccessController@del');
