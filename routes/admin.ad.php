<?php
Route::group(['prefix' => '/Ad'], function () {
    //广告列表页
    Route::any('adList', 'Admin\AdController@adList');
    //广告位置列表页
    Route::get('positionList', 'Admin\AdController@positionList');
    //新增广告
    Route::get('ad', 'Admin\AdController@ad');
    //处理广告的操作
    Route::any('adHandle', 'Admin\AdController@adHandle');
    //编辑广告页面显示
    Route::any('edit/{id}', 'Admin\AdController@edit');
    //ajax异步更新广告排序
    Route::post('changeOrder', 'Admin\AdController@changeOrder');
    //新增广告位
    Route::get('position', 'Admin\AdController@position');
    //处理广告位的操作
    Route::post('positionHandle', 'Admin\AdController@positionHandle');
    //编辑广告位页面
    Route::any('positionEdit/{id}', 'Admin\AdController@positionEdit');
    //查看广告页面
    Route::get('positionShow/{id}', 'Admin\AdController@positionShow');
});