<?php
//文章路由
Route::resource('Article/articleList', 'Admin\ArticleController');
//根据条件搜索文章
Route::post('Article/articleList/search', 'Admin\ArticleController@search');
//文章分类列表
Route::get('Article/categoryList', 'Admin\ArticleController@getCategoryList');
//新增分类
Route::get('Article/category', 'Admin\ArticleController@category');
//处理新增文章分类和删除文章分类
Route::any('Article/categoryHandle', 'Admin\ArticleController@categoryHandle');
//编辑文章分类
Route::any('Article/categoryEdit/{id}', 'Admin\ArticleController@categoryEdit');
//友情链接列表
Route::any('Article/linkList', 'Admin\ArticleController@linkList');
//新增友情链接
Route::get('Article/link', 'Admin\ArticleController@link');
//处理友情链接的操作
Route::any('Article/linkHandle', 'Admin\ArticleController@linkHandle');
//编辑友情链接
Route::any('Article/linkEdit/{id}', 'Admin\ArticleController@linkEdit');
//ajax异步改变友情链接的状态
Route::post('Article/changeStatus', 'Admin\ArticleController@changeStatus');
