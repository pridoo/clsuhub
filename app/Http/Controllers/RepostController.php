<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class RepostController extends Controller
{
    public function store(Post $post)
    {
        $user = Auth::user();

        if (!$post->reposts()->where('user_id', $user->id)->exists()) {
            $post->reposts()->create(['user_id' => $user->id]);
        }

        return response()->json([
            'reposts_count' => $post->reposts()->count(),
        ]);
    }
}
