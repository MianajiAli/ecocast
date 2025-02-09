<?php

namespace App\Http\Controllers\Api;

use App\Models\Tag;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Str;  // Add this import at the top of your file
class TagController extends Controller
{
    // Get all tags
    public function index()
    {
        return Tag::all();
    }

    // Show a single tag
    public function show(Tag $tag)
    {
        return response()->json($tag);
    }


    public function store(Request $request)
    {
        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Check if the tag already exists
        $existingTag = Tag::where('name', $request->name)->first();
        if ($existingTag) {
            return response()->json(['message' => 'Tag already exists'], 409);  // 409 Conflict
        }

        // Generate the slug from the 'name'
        $slug = Str::slug($request->name);

        // Create and save the new tag with a slug
        $tag = Tag::create([
            'name' => $request->name,
            'slug' => $slug,  // Automatically add the slug
        ]);

        return response()->json($tag, 201);  // 201 Created
    }


    public function update(Request $request, Tag $tag)
    {
        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Update the name and slug (generate a new slug if the name changes)
        $tag->name = $request->name;
        $tag->slug = Str::slug($request->name);  // Regenerate the slug based on new name
        $tag->save();

        return response()->json($tag);
    }

    // Delete a tag
    public function destroy(Tag $tag)
    {
        $tag->delete();

        return response()->json(['message' => 'Tag deleted successfully'], 200);
    }
}
