<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LikeController extends Controller
{
    public function like(Post $post)
    {
        $post->likes()->attach(auth()->id()); // Attach the logged-in user to the post's likes

        return response()->json(['message' => 'Post liked']);
    }

    public function unlike(Post $post)
    {
        $post->likes()->detach(auth()->id()); // Detach the logged-in user from the post's likes

        return response()->json(['message' => 'Post unliked']);
    }
}
