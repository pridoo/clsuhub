@extends('user.profile.profile')

@section('title', 'My Posts')

@section('tab-content')

<div class="max-w-4xl mx-auto mt-6">

  <h2 class="text-2xl font-semibold mb-6">My Posts</h2>

  @if($posts->isEmpty())
    <p class="text-gray-600">You have not created any posts yet.</p>
  @else
    @foreach ($posts as $post)
      <article class="bg-white rounded-xl p-6 shadow-sm border border-gray-300 text-gray-900 mb-8">

        {{-- Post header --}}
        <div class="flex items-center gap-4 mb-2 relative">
          {{-- User avatar --}}
          <img
            class="w-14 h-14 rounded-full object-cover border border-gray-400"
            src="{{ auth()->user()->profile_photo ? asset('storage/' . auth()->user()->profile_photo) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) }}"
            alt="{{ auth()->user()->name }}"
          />

          <div>
            <div class="font-bold text-gray-800 text-lg">{{ auth()->user()->name }}</div>
            {{-- Upload time --}}
            <div class="text-xs text-gray-500">
              {{ $post->created_at->format('h:i A · M d, Y') }}
            </div>
          </div>

          {{-- Relative time and three dots menu --}}
          <div class="ml-auto flex items-center gap-2">

            <div class="text-xs text-gray-400">{{ $post->created_at->diffForHumans() }}</div>

            {{-- Three dots menu --}}
            <div class="relative">
              <button id="post-menu-btn-{{ $post->id }}" class="text-gray-500 hover:text-gray-700 focus:outline-none" onclick="toggleMenu({{ $post->id }})" aria-label="Post options">
                <i class="fa-solid fa-ellipsis-vertical"></i>
              </button>

              <div id="post-menu-{{ $post->id }}" class="hidden absolute right-0 mt-2 w-28 bg-white border border-gray-300 rounded shadow-md z-10">

                {{-- Hide/Unhide --}}
                <form action="{{ route('posts.toggleHidden', $post->id) }}" method="POST">
                  @csrf
                  <button type="submit" class="block w-full text-left px-4 py-2 hover:bg-gray-100 text-sm text-gray-700">
                    {{ $post->hidden ? 'Unhide' : 'Hide' }}
                  </button>
                </form>

                {{-- Delete --}}
                <form action="{{ route('posts.destroy', $post->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this post?');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="block w-full text-left px-4 py-2 hover:bg-gray-100 text-sm text-red-600">
                    Delete
                  </button>
                </form>

              </div>
            </div>
          </div>
        </div>

        {{-- Post content --}}
        <div class="mb-4 text-base text-gray-900 leading-relaxed">{{ $post->content }}</div>

        {{-- Media --}}
        @if ($post->media->count())
          <div class="flex flex-col gap-4 mb-5">
            @foreach ($post->media as $media)
              @if ($media->type === 'image')
                <img src="{{ asset('storage/' . $media->file_path) }}" class="rounded-lg w-full object-cover max-h-[300px]" alt="Image">
              @elseif ($media->type === 'video')
                <video controls class="rounded-lg w-full max-h-[300px]">
                  <source src="{{ asset('storage/' . $media->file_path) }}">
                  Your browser does not support the video tag.
                </video>
              @else
                <a href="{{ asset('storage/' . $media->file_path) }}" class="text-blue-600 underline" target="_blank">
                  Download Attachment
                </a>
              @endif
            @endforeach
          </div>
        @endif

        {{-- Stats: Likes, Comments, Reposts --}}
        <div class="flex gap-8 text-sm text-gray-600 mb-2">
          <span><i class="fa-solid fa-star"></i> {{ $post->likes_count ?? 0 }} Likes</span>
          <span><i class="fa-solid fa-comment"></i> {{ $post->comments_count ?? 0 }} Comments</span>
          <span><i class="fa-solid fa-repeat"></i> {{ $post->reposts_count ?? 0 }} Reposts</span>
        </div>

      </article>
    @endforeach
  @endif

</div>

<script>
  function toggleMenu(postId) {
    const menu = document.getElementById(`post-menu-${postId}`);
    menu.classList.toggle('hidden');
  }

  window.addEventListener('click', function(e) {
    document.querySelectorAll('[id^="post-menu-"]').forEach(menu => {
      const btn = menu.previousElementSibling;
      if (!menu.contains(e.target) && !btn.contains(e.target)) {
        menu.classList.add('hidden');
      }
    });
  });
</script>

@endsection
