<?php

namespace App\Http\Controllers\Api;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CommentController extends Controller
{
    public function index(Post $post)
    {
        return response()->json($post->comments);
    }

    public function store(Request $request, Post $post)
    {
        $request->validate([
            'body' => 'required|string',
        ]);

        $comment = new Comment();
        $comment->body = $request->body;
        $comment->user_id = auth()->id(); // Authenticated user
        $post->comments()->save($comment);

        return response()->json($comment, 201);
    }

    public function destroy(Post $post, Comment $comment)
    {
        $this->authorize('delete', $comment);

        $comment->delete();

        return response()->json(['message' => 'Comment deleted successfully']);
    }
}
