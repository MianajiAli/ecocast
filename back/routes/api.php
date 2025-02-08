<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\TagController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\LikeController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\SettingController;

// Public Routes
Route::apiResource('categories', CategoryController::class)->only(['index', 'show']);
Route::apiResource('tags', TagController::class)->only(['index', 'show']);
Route::apiResource('posts', PostController::class)->only(['index', 'show']);
Route::apiResource('posts.comments', CommentController::class)->only(['index']);
Route::get('/settings/{key}', [SettingController::class, 'show']); // Public access to settings for specific keys

// Authentication Routes
Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/refresh', [AuthController::class, 'refreshToken']);

    // These routes should be authenticated, so move them inside the middleware
    Route::middleware('auth:api')->group(function () {
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });
});

// Authenticated Routes (Requires API Token)
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
        Route::post('/settings', [SettingController::class, 'store']);
        Route::put('/settings/{key}', [SettingController::class, 'update']);
    });

    // Role-based Middleware for Admins and Authors
    Route::middleware(['role:admin,author'])->group(function () {
        Route::post('/posts', [PostController::class, 'store']);
        Route::put('/posts/{post}', [PostController::class, 'update']);
        Route::delete('/posts/{post}', [PostController::class, 'destroy']);
    });

    // Comment Routes (Authenticated users can post, delete comments)
    Route::apiResource('posts.comments', CommentController::class)->only(['store', 'destroy']);

    // Like Routes
    Route::post('/posts/{post}/like', [LikeController::class, 'like']);
    Route::post('/posts/{post}/unlike', [LikeController::class, 'unlike']);
});
