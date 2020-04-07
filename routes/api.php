<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// ========== 文章 ===========

Route::middleware('auth:api')->put('/article/add', 'Home\ArticleController@add');
Route::middleware('auth:api')->put('/article/update/{id}', 'Home\ArticleController@update');
Route::middleware('auth:api')->put('/website/update/{id}', 'Home\WebSiteController@update');

Route::middleware('auth:api')->post('/article/changeArticleStatus', 'Home\ArticleController@changeArticleStatus');

Route::middleware('auth:api')->post('/article/delete', 'Home\ArticleController@delete');
Route::middleware('auth:api')->post('/website/delete', 'Home\WebSiteController@delete');

Route::middleware('auth:api')->post('/build/all', 'Home\BuildController@runAll');

Route::middleware('auth:api')->post('/website/getGameNamesByWebSiteIDs', 'Home\WebSiteController@getGameNamesByWebSiteIDs');