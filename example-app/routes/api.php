<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\SessionController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/auth/login', [SessionController::class, 'store']);
Route::post('/auth/logout', [SessionController::class, 'destroy'])->middleware('auth:sanctum');

Route::group(['middleware' => ['auth:sanctum']], function () {
    
    Route::get('users/export', [UserController::class, 'exportUsers']);
    Route::post('users/import', [UserController::class, 'importUsers']);

    Route::get('posts/export/', [PostController::class, 'exportPosts']);
    Route::post('posts/import/', [PostController::class, 'importPosts']);
    
    Route::apiResource('users', UserController::class);
    Route::apiResource('posts', PostController::class);
    Route::apiResource('comments', CommentController::class);
});
