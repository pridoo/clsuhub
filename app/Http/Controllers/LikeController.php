<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
public function store(Post $post)
{
    $user = Auth::user();

    // Check if user already liked this post
    $likedBefore = $post->likes()->where('user_id', $user->id)->exists();

    if ($likedBefore) {
        // User already liked it, so unlike (delete)
        $post->likes()->where('user_id', $user->id)->delete();
        $liked = false;
    } else {
        // User has not liked it yet, create new like
        $post->likes()->create(['user_id' => $user->id]);
        $liked = true;
    }

    // Return current likes count
    $likesCount = $post->likes()->count();

    return response()->json([
        'liked' => $liked,
        'likes_count' => $likesCount,
    ]);
}

}
