<?php

namespace App\Http\Controllers\Api;

use App\Models\User;

use App\Http\Controllers\Controller;
use App\Models\Post;

class AuthorController extends Controller
{

    public function getAuthors()
    {
        // Get all users with the 'author' role, ordered by the latest created_at
        $authors = User::role('author')->latest()->limit(4)->get();

        // Check if any authors were found
        if ($authors->isEmpty()) { // Check if the collection is empty
            return response()->json([
                'message' => 'No authors found', // More accurate message
            ], 404);
        }

        // Return the authors' information
        return response()->json([
            'message' => 'Authors found',  // Changed message to plural
            'data' => $authors,           // Return the collection of authors
        ], 200);
    }
    public function getAuthorByUsername($username)
    {
        // Find the user by username
        $user = User::where('username', $username)->first();
        $post = Post::where('user_id', $user->id)->latest()->get();
        // If user not found, return a 404 response
        if (!$user) {
            return response()->json([
                'message' => 'Author not found',
            ], 404);
        }

        // Check if the user has the 'author' role
        if (!$user->hasRole('author')) {
            return response()->json([
                'message' => 'User is not an author',
            ], 403); // Use 403 Forbidden for unauthorized access
        }



        // Return the author's information including posts and roles
        return response()->json([
            'message' => 'Author found',
            'user' => $user,
            'post' => $post
        ], 200);
    }
}
