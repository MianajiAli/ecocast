<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RoleController extends Controller
{
    // Create predefined roles (for API usage only)
    public function createRoles()
    {
        $roles = ['admin', 'manager', 'author', 'user'];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        return response()->json(['message' => 'Roles created successfully'], 201);
    }

    // Assign a role to the user with ID 1 (API usage only)
    public function assignRoleToUser()
    {
        $user = User::find(1);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Assign 'admin' role (or modify as needed)
        $user->assignRole('manager');
        // $user->assignRole('admin');
        // $user->assignRole('author');


        return response()->json([
            'message' => 'Role assigned successfully',
            'user' => $user,
            'isManager' => $user->hasRole('manager'),
            'isAdmin' => $user->hasRole('admin'),
            'isAuthor' => $user->hasRole('author'),
            'isUser' => $user->hasRole('user'),

        ], 200);
    }

    /**
     * Display a listing of the roles.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::all();
        return response()->json($roles);
    }

    /**
     * Create a new role.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
        ]);

        $role = Role::create(['name' => $request->name]);

        return response()->json($role, 201);
    }

    /**
     * Assign permission to role.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Spatie\Permission\Models\Role $role
     * @return \Illuminate\Http\Response
     */
    public function assignPermission(Request $request, Role $role)
    {
        $request->validate([
            'permission' => 'required|exists:permissions,name',
        ]);

        $role->givePermissionTo($request->permission);

        return response()->json([
            'message' => 'Permission assigned successfully',
            'role' => $role->name,
            'permission' => $request->permission
        ]);
    }

    /**
     * Remove permission from a role.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Spatie\Permission\Models\Role $role
     * @return \Illuminate\Http\Response
     */
    public function revokePermission(Request $request, Role $role)
    {
        $request->validate([
            'permission' => 'required|exists:permissions,name',
        ]);

        $role->revokePermissionTo($request->permission);

        return response()->json([
            'message' => 'Permission revoked successfully',
            'role' => $role->name,
            'permission' => $request->permission
        ]);
    }
}
