<?php

use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\TodoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// ユーザー情報取得のルート
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// タスク一覧取得のルート（サンプル）
Route::get('/tasks', [TaskController::class, 'index']);

Route::controller(TodoController::class)->group(function () {
    Route::get('/todos', 'index')->name('todos.index');
    Route::post('/todos', 'store')->name('todos.store');
});
