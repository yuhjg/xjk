<?php
// 前台路由
Route::get('/', 'index/Index/index');
Route::get('about', 'index/About/index');

Route::get('product/:id', 'index/Product/detail');
Route::get('news/:id', 'index/News/detail');
Route::get('contact', 'index/Contact/index');
Route::post('contact/submit', 'index/Contact/submit');
Route::get('news', 'index/News/index');
Route::get('product', 'index/Product/index');

// 后台路由
Route::get('admin/logins', 'admin/Login/index');
Route::post('admin/login', 'admin/Login/doLogin');
Route::get('admin/logout', 'admin/Login/logout');
Route::get('admin/index', 'admin/Index/index');
Route::get('admin/welcome', 'admin/Index/welcome');

// 后台产品
Route::get('admin/product/add', 'admin/Product/add');
Route::post('admin/product/add', 'admin/Product/add');
Route::get('admin/product/edit/:id', 'admin/Product/edit');
Route::post('admin/product/edit/:id', 'admin/Product/edit');
Route::post('admin/product/delete', 'admin/Product/delete');
Route::post('admin/product/setStatus', 'admin/Product/setStatus');
Route::get('admin/product', 'admin/Product/index');

// 后台产品分类
Route::get('admin/product_category/add', 'admin/ProductCategory/add');
Route::post('admin/product_category/add', 'admin/ProductCategory/add');
Route::get('admin/product_category/edit/:id', 'admin/ProductCategory/edit');
Route::post('admin/product_category/edit/:id', 'admin/ProductCategory/edit');
Route::post('admin/product_category/delete', 'admin/ProductCategory/delete');
Route::get('admin/product_category', 'admin/ProductCategory/index');

// 后台新闻
Route::get('admin/news/add', 'admin/News/add');
Route::post('admin/news/add', 'admin/News/add');
Route::get('admin/news/edit/:id', 'admin/News/edit');
Route::post('admin/news/edit/:id', 'admin/News/edit');
Route::post('admin/news/delete', 'admin/News/delete');
Route::post('admin/news/setStatus', 'admin/News/setStatus');
Route::get('admin/news', 'admin/News/index');

// 后台新闻分类
Route::get('admin/news_category/add', 'admin/NewsCategory/add');
Route::post('admin/news_category/add', 'admin/NewsCategory/add');
Route::get('admin/news_category/edit/:id', 'admin/NewsCategory/edit');
Route::post('admin/news_category/edit/:id', 'admin/NewsCategory/edit');
Route::post('admin/news_category/delete', 'admin/NewsCategory/delete');
Route::get('admin/news_category', 'admin/NewsCategory/index');

// 后台公司信息
Route::get('admin/company', 'admin/Company/index');
Route::post('admin/company', 'admin/Company/index');

// 后台轮播图
Route::get('admin/banner/add', 'admin/Banner/add');
Route::post('admin/banner/add', 'admin/Banner/add');
Route::get('admin/banner/edit/:id', 'admin/Banner/edit');
Route::post('admin/banner/edit/:id', 'admin/Banner/edit');
Route::post('admin/banner/delete', 'admin/Banner/delete');
Route::get('admin/banner', 'admin/Banner/index');

// 后台留言管理
Route::get('admin/message/detail/:id', 'admin/Message/detail');
Route::post('admin/message/delete', 'admin/Message/delete');
Route::post('admin/message/setRead', 'admin/Message/setRead');
Route::get('admin/message', 'admin/Message/index');

return [];
