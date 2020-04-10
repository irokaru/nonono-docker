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
