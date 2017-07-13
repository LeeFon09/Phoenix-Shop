<?php

//商品评价
Route::get('Comment/index', 'Admin\CommentController@index');
//根据商品评价ID删除该评价
Route::get('comment/del/{id}', 'Admin\CommentController@del');
//查看商品评价详情
Route::get('comment/detail/{id}', 'Admin\CommentController@detail');
//回复评价
Route::post('comment/reply/{id}', 'Admin\CommentController@reply');
//ajax修改商品评论状态
Route::post('Comment/changeStatus', 'Admin\CommentController@changeStatus');
