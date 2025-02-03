<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\TagController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\LikeController;
use App\Http\Controllers\Api\UserController; // Assuming there's a UserController for managing users

// Authentication Routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/refresh', [AuthController::class, 'refreshToken']);

Route::middleware(['auth:api'])->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Role-based Middleware for Admins
    Route::middleware(['role:admin'])->group(function () {
        // Admin routes for managing categories, tags, etc.
        Route::apiResource('categories', CategoryController::class)->except(['index', 'show']);
        Route::apiResource('tags', TagController::class)->except(['index', 'show']);

        // Admin routes for posts
        Route::post('/posts', [PostController::class, 'store']);
        Route::put('/posts/{post}', [PostController::class, 'update']);
        Route::delete('/posts/{post}', [PostController::class, 'destroy']);
    });

// Role-based Middleware for Super Admins
Route::middleware(['role:super_admin'])->group(function () {
    // Super Admin routes for user management
    Route::apiResource('users', UserController::class); // Full user management
    Route::put('/users/{user}/assign-role', [UserController::class, 'assignRole']); // Example for role assignment

    // Super Admin can also manage categories and tags
    Route::apiResource('categories', CategoryController::class)->only(['index', 'show']);
    Route::apiResource('tags', TagController::class)->only(['index', 'show']);

    // Super Admin-only route for managing site-wide settings
    Route::get('/settings', [SettingController::class, 'index']); // Get all settings
    Route::get('/settings/{key}', [SettingController::class, 'show']); // Get specific setting by key
    Route::post('/settings', [SettingController::class, 'store']); // Create a new setting
    Route::put('/settings/{key}', [SettingController::class, 'update']); // Update a specific setting
});


    // Role-based Middleware for Admins and Authors
    Route::middleware(['role:admin,author'])->group(function () {
        // Post routes for creating and editing posts
        Route::post('/posts', [PostController::class, 'store']);
        Route::put('/posts/{post}', [PostController::class, 'update']);
        Route::delete('/posts/{post}', [PostController::class, 'destroy']);
    });

    // Comment Routes (Anyone can comment, but only the author can delete their comments)
    Route::apiResource('posts.comments', CommentController::class)->only(['index', 'store', 'destroy']);

    // Like Routes (Logged-in users can like/unlike posts)
    Route::post('/posts/{post}/like', [LikeController::class, 'like']);
    Route::post('/posts/{post}/unlike', [LikeController::class, 'unlike']);
});
