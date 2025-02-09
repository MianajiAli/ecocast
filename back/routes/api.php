<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes (Version 1)
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {
    // Authentication Routes
    Route::prefix('auth')->group(function () {
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/refresh', [AuthController::class, 'refreshToken']);

        // Protected Authentication Routes
        Route::middleware('auth:api')->group(function () {
            Route::get('/me', [AuthController::class, 'me']); // Get authenticated user details
            Route::post('/logout', [AuthController::class, 'logout']);
        });
    });

    // User Management Routes (Protected)
    Route::middleware('auth:api')->group(function () {
        // Route::get('/users', [UserController::class, 'index']); // Get all users
        // Route::get('/users/{id}', [UserController::class, 'show']); // Get a specific user
        // Route::put('/users/{id}', [UserController::class, 'update']); // Update user
        // Route::delete('/users/{id}', [UserController::class, 'destroy']); // Delete user
    });

    // Post Management Routes (Example of CRUD Operations)
    Route::middleware('auth:api')->group(function () {
        // Route::get('/posts', [PostController::class, 'index']); // Get all posts
        // Route::post('/posts', [PostController::class, 'store']); // Create a post
        // Route::get('/posts/{id}', [PostController::class, 'show']); // Get a specific post
        // Route::put('/posts/{id}', [PostController::class, 'update']); // Update post
        // Route::delete('/posts/{id}', [PostController::class, 'destroy']); // Delete post
    });
});
