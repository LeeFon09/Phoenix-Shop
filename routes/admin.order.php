<?php
Route::group(['prefix' => '/Order'], function () {
    //订单列表
    Route::any('index','Admin\OrderController@index');
    //订单列表处理状态的操作
    Route::post('orderHandle', 'Admin\OrderController@orderHandle');
    //退货单列表及其筛选操作
    Route::any('return_list', 'Admin\OrderController@returnList');
    //删除退货单信息
    Route::post('returnDel', 'Admin\OrderController@returnDel');
    //查看退货单信息
    Route::get('return_info/{id}', 'Admin\OrderController@returnInfo');
    //编辑退货单信息
    Route::post('returnEdit', 'Admin\OrderController@returnEdit');
    //ajax异步改变状态
    Route::post('changeStatus', 'Admin\OrderController@changeStatus');
});