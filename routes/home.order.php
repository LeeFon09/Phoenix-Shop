<?php

//我的订单
Route::get('home/order/orderList/{status?}','Home\OrderController@orderList');
//订单详情跳转
Route::get('home/order/orderDetail/{oid}','Home\OrderController@orderDetail');
//取消订单
Route::post('home/order/concelOrder/{oid}','Home\OrderController@concelOrder');
//订单支付跳转
Route::post('home/order/toPay/{oid}','Home\OrderController@toPay');
//订单支付操作
Route::post('home/order/doPay/{oid}','Home\OrderController@doPay');
//收货确认操作
Route::post('home/order/confirmOrder/{oid}','Home\OrderController@confirmOrder');


