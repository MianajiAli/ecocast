<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RoleController;

/*
|--------------------------------------------------------------------------
| API Routes (Version 1)
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {
        Route::get('/create-roles', [RoleController::class, 'createRoles']);
        Route::get('/assign-to-user', [RoleController::class, 'assignRoleToUser']);

    // Authentication Routes
    Route::prefix('auth')->group(function () {
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/refresh', [AuthController::class, 'refreshToken']);

        // Protected Authentication Routes
        Route::middleware('auth:api')->group(function () {
            Route::post('/me', [AuthController::class, 'me']); // Get authenticated user details
            Route::post('/logout', [AuthController::class, 'logout']);
        });
    });

    // Role Management Routes (Only accessible by 'admin' or 'manager')
    Route::prefix('roles')->middleware(['auth:api', 'role:manager|admin'])->group(function () {
        Route::post('/me', [AuthController::class, 'me']); // Get authenticated user details

        Route::get('/', [RoleController::class, 'index']); // Get all roles
        Route::post('/store', [RoleController::class, 'store']); // Create a new role

        Route::post('/{role}/assign-permission', [RoleController::class, 'assignPermission']); // Assign permission to role
        Route::post('/{role}/revoke-permission', [RoleController::class, 'revokePermission']); // Revoke permission from role
    });
});
