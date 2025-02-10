<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller {
    public function index() {
        $posts = Post::latest()->get();
        return response()->json([
            'success' => true,
            'data' => $posts
        ], 200);
    }

    public function show($slug) {
        $post = Post::where('slug', $slug)->firstOrFail();
        return response()->json([
            'success' => true,
            'data' => $post
        ], 200);
    }

    public function store(Request $request) {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|unique:posts,slug',
            'content' => 'required|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:255'
        ]);

        $post = Post::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Post created successfully',
            'data' => $post
        ], 201);
    }

    public function update(Request $request, $slug) {
        $post = Post::where('slug', $slug)->firstOrFail();

        $request->validate([
            'title' => 'sometimes|string|max:255',
            'slug' => 'sometimes|string|unique:posts,slug,' . $post->id,
            'content' => 'sometimes|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:255'
        ]);

        $post->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Post updated successfully',
            'data' => $post
        ], 200);
    }

    public function destroy($slug) {
        $post = Post::where('slug', $slug)->firstOrFail();
        $post->delete();

        return response()->json([
            'success' => true,
            'message' => 'Post deleted successfully'
        ], 200);
    }
}
