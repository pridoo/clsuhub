<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
public function store(Request $request, Post $post)
{
    $request->validate([
        'comment' => 'required|string|max:1000',
        'parent_id' => 'nullable|exists:comments,id',
    ]);

    $comment = $post->comments()->create([
        'user_id' => Auth::id(),
        'comment' => $request->comment,
        'parent_id' => $request->parent_id,
    ]);

    if ($request->expectsJson()) {
        return response()->json([
            'success' => true,
            'comment' => [
                'id' => $comment->id,
                'user_name' => $comment->user->name,
                'comment' => $comment->comment,
                'post_id' => $post->id,
                'category' => $post->category,
            ],
        ]);
    }

    return redirect()->back()->with('success', 'Comment posted!');
}

}
