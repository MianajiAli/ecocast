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
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Image validation
            'status' => ['nullable', Rule::in(['draft', 'published', 'archived'])], // Status validation
            'category' => 'nullable|string|max:100',
            'tags' => 'nullable|array', // Ensure tags are an array
        ]);

        // Add user_id to the validated data
        $validatedData['user_id'] = auth()->id();

        if ($request->hasFile('thumbnail')) {
            $validatedData['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        $post = Post::create($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Post created successfully',
            'data' => $post
        ], 201);
    }

    public function update(Request $request, $slug)
    {
        $post = Post::where('slug', $slug)->firstOrFail();

        $validatedData = $request->validate([
            'title' => 'sometimes|string|max:255',
            'slug' => 'sometimes|string|unique:posts,slug,' . $post->id,
            'content' => 'sometimes|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:255',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => ['sometimes', Rule::in(['draft', 'published', 'archived'])],
            'category' => 'nullable|string|max:100',
            'tags' => 'nullable|array',
        ]);

        if ($request->hasFile('thumbnail')) {
            if ($post->thumbnail) {
                Storage::disk('public')->delete($post->thumbnail); // Delete old thumbnail
            }
            $validatedData['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        // Don't change the user_id during an update
        unset($validatedData['user_id']);

        $post->update($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Post updated successfully',
            'data' => $post
        ], 200);
    }


    public function destroy($slug)
    {
        $post = Post::where('slug', $slug)->firstOrFail();

        if ($post->thumbnail) {
            Storage::disk('public')->delete($post->thumbnail); // Delete thumbnail if exists
        }

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
