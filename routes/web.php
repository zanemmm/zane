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

Route::get('/', 'BackendRenderController@index');
Route::post('/', 'FrontendRenderController@index');

Route::group(['middleware' => 'search'], function () {
    Route::get('/search/{keyword}', 'BackendRenderController@search');
    Route::post('/search/{keyword}', 'FrontendRenderController@search');
});

Route::group(['middleware' => 'render:category'], function () {
    Route::get('/categories/{category}', 'BackendRenderController@category');
    Route::post('/categories/{category}', 'FrontendRenderController@category');
});

Route::group(['middleware' => 'render:post'], function () {
    Route::get('/posts/{post}', 'BackendRenderController@post');
    Route::post('/posts/{post}', 'FrontendRenderController@post');
});

Route::group(['middleware' => 'render:tag'], function () {
    Route::get('/tags/{tag}', 'BackendRenderController@tag');
    Route::post('/tags/{tag}', 'FrontendRenderController@tag');
});