<?php

use App\Http\Controllers\API\TaskController;
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

// タスク一覧取得のルート
Route::get('/tasks', [TaskController::class, 'index']);
