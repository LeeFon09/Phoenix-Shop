<?php
//商品类型列表路由
Route::resource('Goods/categoryList', 'Admin\Goods\CategoryListController');
//获得商品子分类
Route::any('Goods/categoryList/getCategory', 'Admin\Goods\CategoryListController@getCategory');
//删除商品分类
Route::any('Goods/categoryList/del/{id}', 'Admin\Goods\CategoryListController@del');
//ajax修改商品列表状态
Route::post('Goods/categoryList/changeStatus', 'Admin\Goods\CategoryListController@changeStatus');



//删除商品路由
Route::get('Goods/goodsList/del/{id}', 'Admin\Goods\GoodsController@del');
//商品路由
Route::resource('Goods/goodsList', 'Admin\Goods\GoodsController');
//ajax修改商品列表状态
Route::post('Goods/goodsList/changeStatus', 'Admin\Goods\GoodsController@changeStatus');
//商品图片上传弹框路由
Route::any('Goods/shopUpload', 'Admin\Goods\GoodsController@shopUpload');
//商品缩略图上传弹框路由
Route::any('Goods/upload', 'Admin\Goods\GoodsController@upload');
//商品图片上传弹框路由
Route::any('Goods/specUpload', 'Admin\Goods\GoodsController@specUpload');
//列出商品图片
Route::any('Goods/saveImg', 'Admin\Goods\GoodsController@saveImg');
//去掉商品图片
Route::any('Goods/delupload', 'Admin\Goods\GoodsController@delupload');
//ajax获取商品规格信息
Route::any('Goods/ajaxGetSpec', 'Admin\Goods\GoodsController@ajaxGetSpec');
//ajax获取商品规格输入框选项
Route::any('Goods/ajaxGetSpecInput', 'Admin\Goods\GoodsController@ajaxGetSpecInput');
//修改商品时ajax获取商品属性输入框选项
Route::any('Goods/getAttrInput2', 'Admin\Goods\GoodsController@getAttrInput2');
//ajax获取商品属性输入框选项
Route::any('Goods/getAttrInput', 'Admin\Goods\GoodsController@getAttrInput');


//商品类型路由
Route::resource('Goods/goodsTypeList', 'Admin\Goods\GoodsTypeListController');
//根据商品类型ID删除该类型
Route::get('Goods/goodsTypeList/del/{id}', 'Admin\Goods\GoodsTypeListController@del');
//商品规格路由
Route::resource('Goods/specList', 'Admin\Goods\SpecListController');
//删除商品规格
Route::get('Goods/specList/del/{id}', 'Admin\Goods\SpecListController@del');
//搜索单个商品类型的规格
Route::any('Goods/specList/search', 'Admin\Goods\SpecListController@search');
//ajax修改商品规格状态
Route::post('Goods/specList/changeStatus', 'Admin\Goods\SpecListController@changeStatus');



//商品属性路由
Route::resource('Goods/goodsAttributeList', 'Admin\Goods\GoodsAttributeListController');
//删除商品属性
Route::get('Goods/goodsAttributeList/del/{id}', 'Admin\Goods\GoodsAttributeListController@del');
//搜索商品类型对应的属性
Route::any('Goods/goodsAttributeList/search/{id?}', 'Admin\Goods\GoodsAttributeListController@search');
//ajax修改商品属性状态
Route::post('Goods/goodsAttributeList/changeStatus', 'Admin\Goods\GoodsAttributeListController@changeStatus');


//商品品牌路由
Route::resource('Goods/brandList', 'Admin\Goods\BrandListController');
//根据商品品牌ID删除该品牌
Route::get('Goods/brandList/del/{id}', 'Admin\Goods\BrandListController@del');
//ajax修改商品评论状态
Route::post('Goods/brandList/changeStatus', 'Admin\Goods\BrandListController@changeStatus');
