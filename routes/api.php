<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function () {
    // authentication
    Route::get('/logout', [AuthenticationController::class, 'logout']);
    Route::get('/me', [AuthenticationController::class, 'me']);
    // untuk post
    Route::post('/posts', [PostController::class, 'store']);
    Route::middleware('pemilik-postingan')->group(function () {
        Route::patch('/posts/{id}', [PostController::class, 'update']);
        Route::delete('/posts/{id}', [PostController::class, 'destroy']);
    });
    // untuk comment
    Route::post('/comments', [CommentController::class, 'store']);
    Route::middleware('pemilik-komentar')->group(function () {
        Route::patch('/comments/{id}', [CommentController::class, 'update']);
        Route::delete('/comments/{id}', [CommentController::class, 'destroy']);
    });
});

// post
Route::get('/posts', [PostController::class, 'index']);
Route::get('/posts/{id}', [PostController::class, 'show']);
// auth
Route::post('/login', [AuthenticationController::class, 'login']);
