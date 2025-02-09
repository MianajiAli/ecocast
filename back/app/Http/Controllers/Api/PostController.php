<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::all();
        return response()->json($posts, 200);
    }

    public function show(Post $post)
    {
        return response()->json($post);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|unique:posts,slug', // ✅ Added slug validation
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
        ]);

        $user = Auth::user();
        if (!$user || !$user->hasRole('author')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $post = new Post();
        $post->fill([
            'title' => $request->title,
            'slug' => $request->slug, // ✅ Added slug
            'content' => $request->content,
            'category_id' => $request->category_id,
            'user_id' => $user->id,
        ]);
        $post->save();

        return response()->json($post, 201);
    }

    public function update(Request $request, Post $post)
    {
        $this->authorize('update', $post);

        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|unique:posts,slug,' . $post->id, // ✅ Slug must be unique except for the current post
            'content' => 'required|string',
        ]);

        $post->update($request->only(['title', 'slug', 'content'])); // ✅ Updated slug & content

        return response()->json($post);
    }

    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);

        $post->delete();

        return response()->json(['message' => 'Post deleted successfully']);
    }
}
