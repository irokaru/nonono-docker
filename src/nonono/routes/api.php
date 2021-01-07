<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\PostController;

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
    Route::post('login', [AuthController::class, 'login'])->name('auth.login');
    Route::get('refresh', [AuthController::class, 'refresh'])->name('auth.refresh');

    Route::middleware('auth:api')->group(function () {
        Route::post('logout', [AuthController::class, 'logout'])->name('auth.logout');
    });
});

Route::get('history', [HistoryController::class, 'index'])->name('history.index');
Route::post('history', [HistoryController::class, 'store'])->name('history.store');
Route::put('history', 'HistoryController@update')->name('history.update');
Route::delete('history/{id}', 'HistoryController@delete')->name('history.delete');

Route::get('games', [GameController::class, 'index'])->name('games.index');
Route::get('games/all', [GameController::class, 'indexAll'])->name('games.index.all');
Route::post('games', [GameController::class, 'store'])->name('games.store');
Route::put('games', 'GameController@update')->name('games.update');

Route::get('posts', [PostController::class, 'index'])->name('post.index');
Route::post('posts', [PostController::class, 'store'])->name('post.store');
Route::put('posts', 'PostController@update')->name('post.update');
Route::get('posts/all', [PostController::class, 'indexAll'])->name('post.index.all');
Route::get('posts/latest/{limit}', [PostController::class, 'indexLatest'])->name('post.index.latest');
Route::get('posts/category/{category}', [PostController::class, 'indexAsCategory'])->name('post.index.category');
Route::get('post/show/{post_id}', [PostController::class, 'show'])->name('post.show');
Route::get('posts/categories', [PostController::class, 'indexOfCategories'])->name('post.index.categories');
