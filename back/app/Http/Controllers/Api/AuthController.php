<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Laravel\Passport\RefreshToken;
use Laravel\Passport\Token;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    /**
     * Register a new user
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255|unique:users,username', // Unique username
            'name' => 'required|string|max:255',
            'phone' => 'required|string|unique:users,phone',
            'password' => 'required|string|min:6',
            'profile_image' => 'nullable|url', // Optional profile image URL
            'banner_image' => 'nullable|url', // Optional banner image URL
            'bio' => 'nullable|string', // Optional bio
            'social_links' => 'nullable|array', // Optional social links (array)
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'username' => $request->username,
            'name' => $request->name,
            'phone' => $request->phone,
            'password' => bcrypt($request->password),
            'profile_image' => $request->profile_image,
            'banner_image' => $request->banner_image,
            'bio' => $request->bio,
            'social_links' => $request->social_links,
        ]);

        // Generate access token
        $tokenResult = $user->createToken('Personal Access Token');
        $accessToken = $tokenResult->accessToken;

        // Create a refresh token (Passport creates it when the access token is issued)
        $refreshToken = $tokenResult->token->id; // The token's ID is used for the refresh token

        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user,
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
            'token_type' => 'Bearer',
            'expires_at' => $tokenResult->token->expires_at
        ], 201);
    }

    /**
     * Login user and create access & refresh tokens
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if (!Auth::attempt(['phone' => $request->phone, 'password' => $request->password])) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $user = Auth::user();
        $tokenResult = $user->createToken('Personal Access Token');
        $accessToken = $tokenResult->accessToken;
        $refreshToken = $tokenResult->token->id;

        return response()->json([
            'message' => 'Login successful',
            'user' => $user,
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
            'token_type' => 'Bearer',
            'expires_at' => $tokenResult->token->expires_at
        ], 200);
    }

    /**
     * Get user details
     */
    public function me(Request $request)
    {
        return response()->json($request->user());
    }

    /**
     * Check user roles
     */
    public function checkRole(Request $request)
    {
        $user = auth()->user();

        return response()->json([
            'roles' => $user->getRoleNames(),  // Retrieve all roles assigned to the user
        ], 200);
    }

    /**
     * Refresh access token using refresh token (One-time use)
     */
    public function refreshToken(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'refresh_token' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Find the refresh token by ID
        $token = Token::where('id', $request->refresh_token)
            ->where('revoked', false)
            ->first();

        if (!$token) {
            return response()->json(['error' => 'Invalid or already used refresh token'], 401);
        }

        // Revoke the refresh token as it can only be used once
        $token->revoke();

        // Create new access & refresh tokens for the user
        $user = $token->user;
        $tokenResult = $user->createToken('Personal Access Token');
        $newAccessToken = $tokenResult->accessToken;
        $newRefreshToken = $tokenResult->token->id;

        return response()->json([
            'access_token' => $newAccessToken,
            'refresh_token' => $newRefreshToken,
            'token_type' => 'Bearer',
            'expires_at' => $tokenResult->token->expires_at
        ]);
    }

    /**
     * Logout user (Revoke Token & Refresh Token)
     */
    public function logout(Request $request)
    {
        $user = $request->user();

        // Revoke the access token
        $user->token()->revoke();

        // Revoke all refresh tokens for this user
        Token::where('user_id', $user->id)->update(['revoked' => true]);
        RefreshToken::whereIn('access_token_id', function ($query) use ($user) {
            $query->select('id')
                ->from('oauth_access_tokens')
                ->where('user_id', $user->id);
        })->update(['revoked' => true]);

        return response()->json(['message' => 'Successfully logged out'], 200);
    }
}
