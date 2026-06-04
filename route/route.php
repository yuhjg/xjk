<?php
// 前台路由
Route::get('/', 'index/Index/index');
Route::get('about', 'index/About/index');

Route::get('product/:id', 'index/Product/detail');

Route::get('news/:id', 'index/NewsController/detail');
Route::get('contact', 'index/Contact/index');
Route::get('news', 'index/NewsController/index');
Route::get('product', 'index/Product/index');

// 后台路由
Route::get('admin/logins', 'admin/Login/index');
Route::post('admin/login', 'admin/Login/doLogin');
Route::get('admin/logout', 'admin/Login/logout');
Route::get('admin/index', 'admin/Index/index');
Route::get('admin/welcome', 'admin/Index/welcome');

// 后台产品

Route::get('admin/product/add', 'admin/ProductController/add');
Route::post('admin/product/add', 'admin/ProductController/add');
Route::get('admin/product/edit/:id', 'admin/ProductController/edit');
Route::post('admin/product/edit/:id', 'admin/ProductController/edit');
Route::post('admin/product/delete', 'admin/ProductController/delete');
Route::post('admin/product/setStatus', 'admin/ProductController/setStatus');
Route::get('admin/product', 'admin/ProductController/index');

// 后台产品分类
Route::get('admin/product_category/add', 'admin/ProductCategoryController/add');
Route::post('admin/product_category/add', 'admin/ProductCategoryController/add');
Route::get('admin/product_category/edit/:id', 'admin/ProductCategoryController/edit');
Route::post('admin/product_category/edit/:id', 'admin/ProductCategoryController/edit');
Route::post('admin/product_category/delete', 'admin/ProductCategoryController/delete');
Route::get('admin/product_category', 'admin/ProductCategoryController/index');

// 后台新闻
Route::get('admin/news/add', 'admin/NewsController/add');
Route::post('admin/news/add', 'admin/NewsController/add');
Route::get('admin/news/edit/:id', 'admin/NewsController/edit');
Route::post('admin/news/edit/:id', 'admin/NewsController/edit');
Route::post('admin/news/delete', 'admin/NewsController/delete');
Route::post('admin/news/setStatus', 'admin/NewsController/setStatus');
Route::get('admin/news', 'admin/NewsController/index');

// 后台新闻分类
Route::get('admin/news_category/add', 'admin/NewsCategoryController/add');
Route::post('admin/news_category/add', 'admin/NewsCategoryController/add');
Route::get('admin/news_category/edit/:id', 'admin/NewsCategoryController/edit');
Route::post('admin/news_category/edit/:id', 'admin/NewsCategoryController/edit');
Route::post('admin/news_category/delete', 'admin/NewsCategoryController/delete');
Route::get('admin/news_category', 'admin/NewsCategoryController/index');

// 后台公司信息
Route::get('admin/company', 'admin/CompanyController/index');
Route::post('admin/company', 'admin/CompanyController/index');

// 后台轮播图
Route::get('admin/banner/add', 'admin/BannerController/add');
Route::post('admin/banner/add', 'admin/BannerController/add');
Route::get('admin/banner/edit/:id', 'admin/BannerController/edit');
Route::post('admin/banner/edit/:id', 'admin/BannerController/edit');
Route::post('admin/banner/delete', 'admin/BannerController/delete');
Route::get('admin/banner', 'admin/BannerController/index');

return [];
