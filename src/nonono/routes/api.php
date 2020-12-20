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

Route::prefix('auth')->group(function () {
    Route::post('login', 'AuthController@login')->name('auth.login');
    Route::get('refresh', 'AuthController@refresh')->name('auth.refresh');

    Route::middleware('auth:api')->group(function () {
        Route::post('logout', 'AuthController@logout')->name('auth.logout');
    });
});

Route::get('history', 'HistoryController@index')->name('history.index');
Route::post('history', 'HistoryController@store')->name('history.store');
Route::put('history', 'HistoryController@update')->name('history.update');
Route::delete('history/{id}', 'HistoryController@delete')->name('history.delete');

Route::get('games', 'GameController@index')->name('games.index');
Route::get('games/all', 'GameController@indexAll')->name('games.index.all');
Route::post('games', 'GameController@store')->name('games.store');
Route::put('games', 'GameController@update')->name('games.update');

Route::get('posts', 'PostController@index')->name('post.index');
Route::post('posts', 'PostController@store')->name('post.store');
Route::put('posts', 'PostController@update')->name('post.update');
Route::get('posts/all', 'PostController@indexAll')->name('post.index.all');
Route::get('posts/latest/{limit}', 'PostController@indexLatest')->name('post.index.latest');
Route::get('posts/category/{category}', 'PostController@indexAsCategory')->name('post.index.category');
Route::get('post/show/{post_id}', 'PostController@show')->name('post.show');
Route::get('posts/categories', 'PostController@indexOfCategories')->name('post.index.categories');
