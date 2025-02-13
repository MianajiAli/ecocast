<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::latest()->get();
        return response()->json([
            'success' => true,
            'data' => $posts
        ], 200);
    }

    public function show($slug)
    {
        $post = Post::where('slug', $slug)
            ->with(['user:id,name'])
            ->firstOrFail();

        // Increment the view count
        $post->increment('views');

        // Return the response with the updated post data
        return response()->json([
            'success' => true,
            'data' => $post
        ], 200);
    }


    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|unique:posts,slug',
            'content' => 'required|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:255',
            'thumbnail' => 'nullable|string|max:255',
            'status' => ['nullable', Rule::in(['draft', 'published', 'archived'])],
            'category' => 'nullable|string|max:100',
            // Removed 'tags' field
        ]);

        // Add user_id to the validated data
        $validatedData['user_id'] = auth()->id();


        $post = Post::create($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Post created successfully',
            'data' => $post
        ], 201);
    }
    public function update(Request $request, $slug)
    {
        // Validate the incoming data
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => ['required', 'string', Rule::unique('posts', 'slug')->ignore($slug, 'slug')],
            'content' => 'required|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:255',
            'thumbnail' => 'nullable|string|max:255',
            'status' => ['nullable', Rule::in(['draft', 'published', 'archived'])],
            'category' => 'nullable|string|max:100',
        ]);

        // Find the post by slug
        $post = Post::where('slug', $slug)->firstOrFail();

        // Update the post with the validated data
        $post->update($validatedData);

        // If the thumbnail is being updated and there is an existing one, delete it
        if ($request->has('thumbnail') && $request->thumbnail !== $post->thumbnail && $post->thumbnail) {
            Storage::disk('public')->delete($post->thumbnail);
        }

        return response()->json([
            'success' => true,
            'message' => 'Post updated successfully',
            'data' => $post
        ], 200);
    }


    public function destroy($slug)
    {
        $post = Post::where('slug', $slug)->firstOrFail();



        $post->delete();

        return response()->json([
            'success' => true,
            'message' => 'Post deleted successfully'
        ], 200);
    }

    public function restore($slug)
    {
        $post = Post::withTrashed()->where('slug', $slug)->firstOrFail();
        $post->restore();

        return response()->json([
            'success' => true,
            'message' => 'Post restored successfully',
            'data' => $post
        ], 200);
    }
}
