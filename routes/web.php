<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// 网站首页
Route::get('/', 'TopicsController@index')->name('root');

/**
 * 以下代码等同于 Auth::routes();
 */
// Authentication Routes...
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

// Registration Routes...
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register');

// Password Reset Routes...
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset');
/**
 * 以上代码等同于 Auth::routes();
 */

// 注册资源路由
Route::resource('users', 'UsersController', ['only' => ['show', 'update', 'edit']]);
/**
 * 上面代码等同于下面三行代码
Route::get('/users/{user}', 'UsersController@show')->name('users.show'); // 显示个人信息
Route::get('/users/{user}/edit', 'UsersController@edit')->name('users.edit'); // 编辑个人信息
Route::patch('/users/{user}', 'UsersController@update')->name('users.update'); // 处理edit页面提交的更改
*/

// 话题模块路由
Route::resource('topics', 'TopicsController', ['only' => ['index', 'create', 'store', 'update', 'edit', 'destroy']]);
Route::get('topics/{topic}/{slug?}', 'TopicsController@show')->name('topics.show');

// 分类模块路由
Route::resource('categories', 'CategoriesController', ['only' => ['show']]);

// 上传图片
Route::post('upload_image', 'TopicsController@uploadImage')->name('topics.upload_image');

// 回复路由
Route::resource('replies', 'RepliesController', ['only' => ['store', 'destroy']]);

// 通知路由
Route::resource('notifications', 'NotificationsController', ['only' => ['index']]);

// 无权限访问页面
Route::get('permission-denied', 'PagesController@permissionDenied')->name('permission-denied');











