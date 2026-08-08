<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    // === Existing Web Methods (Blade form handlers) ===

    public function store(StoreCommentRequest $request, Post $post)
    {
        Comment::create([
            'body' => $request->input('body'),
            'user_id' => Auth::id(),
            'post_id' => $post->id,
        ]);

        return back()->with('success', 'Comment added successfully.');
    }

    public function destroy(Comment $comment)
    {
        // Check if user is authorized to delete this comment
        if (Auth::id() !== $comment->user_id && Auth::user()->role !== 'admin') {
            return back()->with('error', 'Unauthorized to delete this comment.');
        }

        $post = $comment->post;
        $comment->delete();

        return back()->with('success', 'Comment deleted successfully.');
    }

    // === New API Methods (for React frontend) ===

    public function index(Post $post): JsonResponse
    {
        $comments = $post->comments()->with('user')->get();
        return response()->json(['comments' => CommentResource::collection($comments)]);
    }

    public function apiStore(StoreCommentRequest $request, Post $post): JsonResponse
    {
        $comment = Comment::create([
            'body' => $request->input('body'),
            'user_id' => Auth::id(),
            'post_id' => $post->id,
        ]);

        $comment->load('user');

        return response()->json([
            'comment' => new CommentResource($comment),
            'message' => 'Comment added'
        ]);
    }

    public function apiDestroy(Comment $comment): JsonResponse
    {
        if (Auth::id() !== $comment->user_id && Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $comment->delete();

        return response()->json(['message' => 'Comment deleted']);
    }
}