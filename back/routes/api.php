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
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;


Route::post('/assign-role-to-user', [RoleController::class, 'assignRoleToUser']);

Route::post('/create-roles', [RoleController::class, 'createRoles']);

// 🔹 Public Routes
Route::apiResource('categories', CategoryController::class)->only(['index', 'show']);
Route::apiResource('tags', TagController::class)->only(['index', 'show']);
Route::apiResource('posts.comments', CommentController::class)->only(['index']);
Route::get('/settings/{key}', [SettingController::class, 'show']);

// 🔹 Authentication Routes
Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/refresh', [AuthController::class, 'refreshToken']);

    Route::middleware('auth:api')->group(function () {
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });
});

// 🔹 Authenticated Routes
Route::middleware(['auth:api'])->group(function () {

    // 🔸 Role and Permission Management (Only for Super Admin)
    Route::middleware(['role:super_admin'])->group(function () {

        Route::apiResource('users', UserController::class);
        Route::put('/users/{user}/assign-role', [UserController::class, 'assignRole']);

        Route::apiResource('roles', RoleController::class);
        // Route::apiResource('permissions', PermissionController::class);

        Route::post('/roles/{role}/permissions', [RoleController::class, 'assignPermission']);
        Route::delete('/roles/{role}/permissions', [RoleController::class, 'revokePermission']);
    });

    // 🔸 Admin Only Routes
    Route::middleware(['role:admin'])->group(function () {
        Route::apiResource('categories', CategoryController::class)->except(['index', 'show']);
        Route::apiResource('tags', TagController::class)->except(['index', 'show']);

        Route::post('/posts', [PostController::class, 'store']);
        Route::put('/posts/{post}', [PostController::class, 'update']);
        Route::delete('/posts/{post}', [PostController::class, 'destroy']);
    });

    // 🔸 Admin & Author Routes
    Route::middleware(['role:admin|author'])->group(function () {
        Route::apiResource('posts', PostController::class)->only(['index', 'show']);

        Route::post('/posts', [PostController::class, 'store']);
        Route::put('/posts/{post}', [PostController::class, 'update']);
        Route::delete('/posts/{post}', [PostController::class, 'destroy']);
    });

    // 🔹 Comments & Likes (Authenticated Users)
    Route::apiResource('posts.comments', CommentController::class)->only(['store', 'destroy']);
    Route::post('/posts/{post}/like', [LikeController::class, 'like']);
    Route::post('/posts/{post}/unlike', [LikeController::class, 'unlike']);
});
