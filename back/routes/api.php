<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\TagController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\LikeController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\SettingController;

// Authentication Routes
Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/refresh', [AuthController::class, 'refreshToken']);

    Route::middleware(['auth:api'])->group(function () {
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });
});

Route::middleware(['auth:api'])->group(function () {
    // Role-based Middleware for Admins
    Route::middleware(['role:admin'])->group(function () {
        Route::apiResource('categories', CategoryController::class)->except(['index', 'show']);
        Route::apiResource('tags', TagController::class)->except(['index', 'show']);
        Route::post('/posts', [PostController::class, 'store']);
        Route::put('/posts/{post}', [PostController::class, 'update']);
        Route::delete('/posts/{post}', [PostController::class, 'destroy']);
    });

    // Role-based Middleware for Super Admins
    Route::middleware(['role:super_admin'])->group(function () {
        Route::apiResource('users', UserController::class);
        Route::put('/users/{user}/assign-role', [UserController::class, 'assignRole']);
        Route::apiResource('categories', CategoryController::class)->only(['index', 'show']);
        Route::apiResource('tags', TagController::class)->only(['index', 'show']);
        Route::get('/settings', [SettingController::class, 'index']);
        Route::get('/settings/{key}', [SettingController::class, 'show']);
        Route::post('/settings', [SettingController::class, 'store']);
        Route::put('/settings/{key}', [SettingController::class, 'update']);
    });

    // Role-based Middleware for Admins and Authors
    Route::middleware(['role:admin,author'])->group(function () {
        Route::post('/posts', [PostController::class, 'store']);
        Route::put('/posts/{post}', [PostController::class, 'update']);
        Route::delete('/posts/{post}', [PostController::class, 'destroy']);
    });

    // Comment Routes
    Route::apiResource('posts.comments', CommentController::class)->only(['index', 'store', 'destroy']);

    // Like Routes
    Route::post('/posts/{post}/like', [LikeController::class, 'like']);
    Route::post('/posts/{post}/unlike', [LikeController::class, 'unlike']);
});
