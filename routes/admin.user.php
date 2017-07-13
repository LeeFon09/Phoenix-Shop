<?php
Route::group(['prefix' => '/User'], function () {
    //会员信息列表
    Route::resource('/', 'Admin\UserController');
    //编辑会员信息
    Route::get('/userEdit/{id}', 'Admin\UserController@userEdit');
    //处理会员信息的操作
    Route::any('/userHandle', 'Admin\UserController@userHandle');
    //删除会员信息
    Route::post('/userDel', 'Admin\UserController@userDel');
    //筛选会员信息
    Route::any('/search', 'Admin\UserController@search');
    //给会员发送邮箱信息
    Route::get('/sendMail', 'Admin\UserController@sendMail');
    //处理发送邮箱信息的操作
    Route::any('/doSendMail', 'Admin\UserController@doSendMail');
    //会员等级列表
    Route::get('/levelList', 'Admin\UserController@levelList');
    //新增会员等级
    Route::get('/level', 'Admin\UserController@level');
    //处理会员等级的操作
    Route::any('/levelHandle', 'Admin\UserController@levelHandle');
    //删除会员等级
    Route::post('/levelDel', 'Admin\UserController@levelDel');
    //编辑会员等级
    Route::get('/levelEdit/{id}', 'Admin\UserController@levelEdit');
});

