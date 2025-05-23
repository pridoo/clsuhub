<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\PostMedia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $departmentId = DB::table('form_responses')->where('user_id', $user->id)->value('department_id');

        // Public posts: only order by created_at descending (latest first)
        $publicPosts = Post::with([
            'media',
            'user',
            'comments' => function ($q) {
                $q->whereNull('parent_id')->with('user', 'replies.user', 'replies.replies.user');
            },
        ])
            ->withCount(['likes', 'comments', 'reposts'])
            ->where('hidden', false)
            ->where('privacy', 'public')
            ->orderByDesc('created_at')  // latest first only
            ->get();

        $publicPosts->each(fn($post) => $post->category = 'public');

        // Department posts - ordered latest first as before
        $departmentPosts = collect();
        if ($departmentId) {
            $departmentPosts = Post::with([
                'media',
                'user',
                'comments' => function ($q) {
                    $q->whereNull('parent_id')->with('user', 'replies.user', 'replies.replies.user');
                },
            ])
                ->withCount(['likes', 'comments', 'reposts'])
                ->where('hidden', false)
                ->where('privacy', 'department')
                ->where('department_id', $departmentId)
                ->orderByDesc('created_at')
                ->get();

            $departmentPosts->each(fn($post) => $post->category = 'department');
        }

        // Merge only public and department posts, no trending
        $allPosts = $publicPosts->merge($departmentPosts)
            ->unique('id')    // <-- Remove duplicates if any
            ->sortByDesc('created_at')
            ->values();  // reset keys

        // Alumni list unchanged
        $alumni = DB::table('form_responses')
            ->join('users', 'form_responses.user_id', '=', 'users.id')
            ->select(
                'form_responses.full_name as full_name',
                DB::raw('YEAR(form_responses.graduation_year) as batch_year'),
                'users.profile_photo'
            )
            ->distinct()
            ->orderByDesc('batch_year')
            ->get();

        return view('user.dashboard', [
            'posts' => $allPosts,
            'alumni' => $alumni,
        ]);
    }





    public function show($id)
    {
        $post = Post::with(['media', 'user', 'comments.user'])->findOrFail($id);

        $alumni = DB::table('form_responses')
            ->join('users', 'form_responses.user_id', '=', 'users.id')
            ->select(
                'form_responses.full_name as full_name',
                DB::raw('YEAR(form_responses.graduation_year) as batch_year'),
                'users.profile_photo'
            )
            ->distinct()
            ->orderByDesc('batch_year')
            ->get();

        return view('posts.show', compact('post', 'alumni'));
    }



    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
            'media.*' => 'nullable|file|max:512000',
            'privacy' => 'required|in:public,department',
        ]);

        $userId = Auth::id();


        $departmentId = DB::table('form_responses')
            ->where('user_id', $userId)
            ->value('department_id');

        if ($request->privacy === 'department' && !$departmentId) {
            return redirect()->back()->withErrors(['department' => 'No department assigned to your account. Contact admin.']);
        }

        $post = new Post();
        $post->user_id = $userId;
        $post->content = $request->content;
        $post->privacy = $request->privacy;
        $post->hidden = false;
        $post->department_id = $request->privacy === 'department' ? $departmentId : null;
        $post->save();

        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $file) {
                $path = $file->store('post_media', 'public');

                $mimeType = $file->getMimeType();
                $type = 'attachment';
                if (str_starts_with($mimeType, 'image/')) {
                    $type = 'image';
                } elseif (str_starts_with($mimeType, 'video/')) {
                    $type = 'video';
                }

                PostMedia::create([
                    'post_id' => $post->id,
                    'file_path' => $path,
                    'type' => $type,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Post created successfully!');
    }

    public function update(Request $request)
    {
        $request->validate([
            'post_id' => 'required|exists:posts,id',
            'content' => 'required|string|max:1000',
            'media' => 'nullable|file|max:512000',
            'privacy' => 'required|in:public,department',
        ]);

        $post = Post::findOrFail($request->post_id);

        if ($post->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $post->content = $request->content;
        $post->privacy = $request->privacy;


        if ($request->hasFile('media')) {
            $post->media()->each(function ($media) {
                Storage::disk('public')->delete($media->file_path);
                $media->delete();
            });

            $file = $request->file('media');
            $path = $file->store('post_media', 'public');
            $mimeType = $file->getMimeType();
            $type = 'attachment';
            if (str_starts_with($mimeType, 'image/')) {
                $type = 'image';
            } elseif (str_starts_with($mimeType, 'video/')) {
                $type = 'video';
            }

            PostMedia::create([
                'post_id' => $post->id,
                'file_path' => $path,
                'type' => $type,
            ]);
        }

        $post->save();

        return redirect()->back()->with('success', 'Post updated successfully!');
    }

    public function toggleHidden(Request $request, $postId)
    {
        $post = Post::findOrFail($postId);

        if ($post->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $post->hidden = !$post->hidden;
        $post->save();

        return redirect()->back()->with('success', 'Post visibility updated.');
    }

    public function destroy($postId)
    {
        $post = Post::findOrFail($postId);

        if ($post->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $post->media()->each(function ($media) {
            Storage::disk('public')->delete($media->file_path);
            $media->delete();
        });

        $post->delete();

        return redirect()->back()->with('success', 'Post deleted successfully.');
    }
}
