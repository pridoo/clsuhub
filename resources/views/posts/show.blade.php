@extends('layouts.main')

@section('title', 'Post Details')

@section('content')
<div class="max-w-3xl mx-auto mt-10 px-4 sm:px-6 lg:px-8">

    {{-- Back button --}}
    <a href="{{ url()->previous() }}" 
       class="inline-flex items-center gap-2 mb-6 text-blue-600 hover:underline text-sm font-medium">
        <i class="fa-solid fa-arrow-left"></i> Back
    </a>

    <article class="bg-white rounded-xl p-6 shadow-sm border border-gray-300 text-gray-900">

        {{-- Post header --}}
        <div class="flex items-center gap-4 mb-2 relative">

            {{-- User avatar --}}
            <img
                class="w-14 h-14 rounded-full object-cover border border-gray-400"
                src="{{ $post->user->profile_photo ? asset('storage/' . $post->user->profile_photo) : 'https://ui-avatars.com/api/?name=' . urlencode($post->user->name) }}"
                alt="{{ $post->user->name }}"
            />

            <div>
                <div class="font-bold text-gray-800 text-lg">{{ $post->user->name }}</div>
                <div class="text-xs text-gray-500">
                    {{ $post->created_at->format('h:i A') }} · {{ ucfirst($post->privacy ?? 'Public') }}
                </div>
            </div>

            {{-- Relative time and post menu --}}
            <div class="ml-auto flex items-center gap-2">

                <div class="text-xs text-gray-400">{{ $post->created_at->diffForHumans() }}</div>

                @if(auth()->id() === $post->user_id)
                    <div class="relative">
                        <button
                            id="post-menu-btn-{{ $post->id }}"
                            class="text-gray-500 hover:text-gray-700 focus:outline-none"
                            onclick="toggleMenu({{ $post->id }})"
                            aria-label="Post options"
                        >
                            <i class="fa-solid fa-ellipsis-vertical"></i>
                        </button>

                        <div
                            id="post-menu-{{ $post->id }}"
                            class="hidden absolute right-0 mt-2 w-32 bg-white border border-gray-300 rounded shadow-md z-10"
                        >
                            {{-- Hide / Unhide --}}
                            <form action="{{ route('posts.toggleHidden', $post->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="flex items-center gap-2 w-full text-left px-4 py-2 hover:bg-gray-100 text-sm text-gray-700">
                                    <i class="fa-solid {{ $post->hidden ? 'fa-eye' : 'fa-eye-slash' }}"></i>
                                    {{ $post->hidden ? 'Unhide' : 'Hide' }}
                                </button>
                            </form>

                            {{-- Edit --}}
                            <button
                                type="button"
                                class="flex items-center gap-2 w-full text-left px-4 py-2 hover:bg-gray-100 text-sm text-blue-600 cursor-pointer"
                                onclick="openEditModal({{ $post->id }}, `{{ addslashes($post->content) }}`)"
                            >
                                <i class="fa-solid fa-pen-to-square"></i>
                                Edit
                            </button>

                            {{-- Delete --}}
                            <form action="{{ route('posts.destroy', $post->id) }}" method="POST" onsubmit="return confirm('Are you sure to delete this post?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="flex items-center gap-2 w-full text-left px-4 py-2 hover:bg-gray-100 text-sm text-red-600">
                                    <i class="fa-solid fa-trash"></i>
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                @endif

                @include('user.modal.edit-modal')

            </div>
        </div>

        {{-- Post content --}}
        <div class="mb-4 text-base text-gray-900 leading-relaxed">
            {{ $post->content }}
        </div>

        {{-- Media --}}
        @if ($post->media->count())
            <div class="flex flex-col gap-4 mb-5">
                @foreach ($post->media as $media)
                    @if ($media->type === 'image')
                        <img
                            src="{{ asset('storage/' . $media->file_path) }}"
                            class="rounded-lg w-full object-cover max-h-[300px]"
                            alt="Image"
                        >
                    @elseif ($media->type === 'video')
                        <video controls class="rounded-lg w-full max-h-[300px]">
                            <source src="{{ asset('storage/' . $media->file_path) }}">
                            Your browser does not support the video tag.
                        </video>
                    @else
                        <a
                            href="{{ asset('storage/' . $media->file_path) }}"
                            class="text-blue-600 underline"
                            target="_blank"
                        >
                            Download Attachment
                        </a>
                    @endif
                @endforeach
            </div>
        @endif

        {{-- Action buttons --}}
        <div class="flex gap-8 text-sm text-gray-600 mb-2">
            @php
                $userLiked = $post->likedByUser(auth()->user()->id);
            @endphp

            <button
                type="button"
                class="flex items-center gap-2 star-button hover:text-gray-800"
                data-post-id="{{ $post->id }}"
                aria-pressed="{{ $userLiked ? 'true' : 'false' }}"
            >
                <i class="fa-solid fa-star {{ $userLiked ? 'text-yellow-400' : 'text-gray-400' }}"></i>
                Star (<span class="likes-count">{{ $post->likes_count }}</span>)
            </button>

            <button
                class="flex items-center gap-2 hover:text-gray-800"
                onclick="toggleCommentForm({{ $post->id }})"
                type="button"
            >
                <i class="fa-solid fa-comment"></i> Comment ({{ $post->comments_count }})
            </button>

            <form action="{{ route('posts.repost', $post) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="flex items-center gap-2 hover:text-gray-800">
                    <i class="fa-solid fa-repeat"></i> Repost ({{ $post->reposts_count }})
                </button>
            </form>

            <button
                class="flex items-center gap-2 hover:text-gray-800"
                onclick="sharePost({{ $post->id }})"
                type="button"
            >
                <i class="fa-solid fa-share-nodes"></i> Share
            </button>
        </div>

        {{-- Comment form --}}
        <div id="comment-form-{{ $post->id }}" class="hidden mb-4">
            <form action="{{ route('posts.comment', $post) }}" method="POST">
                @csrf
                <textarea
                    name="comment"
                    rows="3"
                    class="w-full p-2 border border-gray-300 rounded"
                    placeholder="Write your comment..."
                    required
                ></textarea>
                <button
                    type="submit"
                    class="mt-2 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
                >
                    Post Comment
                </button>
            </form>
        </div>

        {{-- Comments --}}
        @if ($post->comments->count() > 0)
            <div class="border-t border-gray-200 pt-4 mt-4 max-h-48 overflow-y-auto">
                @foreach ($post->comments as $comment)
                    <div class="flex items-start gap-3 mb-3">
                        <img
                            class="w-8 h-8 rounded-full object-cover border border-gray-300"
                            src="{{ $comment->user->profile_photo ? asset('storage/' . $comment->user->profile_photo) : 'https://ui-avatars.com/api/?name=' . urlencode($comment->user->name) }}"
                            alt="{{ $comment->user->name }}"
                        />
                        <div>
                            <div class="text-sm font-semibold text-gray-800">{{ $comment->user->name }}</div>
                            <div class="text-gray-700 text-sm">{{ $comment->comment }}</div>
                            <div class="text-xs text-gray-400 mt-1">{{ $comment->created_at->diffForHumans() }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    </article>

</div>
@endsection
