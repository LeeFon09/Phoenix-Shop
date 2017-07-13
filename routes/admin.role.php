<?php

//角色路由
Route::resource('Admin/role', 'Admin\RoleController');

//删除角色
Route::get('Admin/role/del/{id}', 'Admin\RoleController@del');
