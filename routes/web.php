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

Route::get('/', ['uses' => 'Home\IndexController@index']);

Route::resource('/article', 'Home\ArticleController');
Route::resource('/site', 'Home\WebSiteController');

Route::get('/user', ['uses' => 'Home\UserController@index']);

Route::get('/error', ['uses' => 'Home\IndexController@error']);

Route::get('/test', function() {

});

Auth::routes();