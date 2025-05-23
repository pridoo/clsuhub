@extends('user.profile.profile')

@section('title', 'Hidden Posts')

@section('tab-content')
<div class="max-w-4xl mx-auto mt-6">

  <h2 class="text-2xl font-semibold mb-6">Hidden Posts</h2>

  @if($posts->isEmpty())
    <p class="text-gray-600">You have no hidden posts.</p>
  @else
    @foreach ($posts as $post)
      <article class="bg-white rounded-xl p-6 shadow-sm border border-gray-300 text-gray-900 mb-8" id="post-{{ $post->id }}">

        <div class="flex items-center gap-4 mb-2 relative">
          <img
            class="w-14 h-14 rounded-full object-cover border border-gray-400"
            src="{{ auth()->user()->profile_photo ? asset('storage/' . auth()->user()->profile_photo) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) }}"
            alt="{{ auth()->user()->name }}"
          />
          <div>
            <div class="font-bold text-gray-800 text-lg">{{ auth()->user()->name }}</div>
            <div class="text-xs text-gray-500">
              {{ $post->created_at->format('h:i A · M d, Y') }}
            </div>
          </div>

          <div class="ml-auto flex items-center gap-2">
            <div class="text-xs text-gray-400">{{ $post->created_at->diffForHumans() }}</div>

            <div class="relative">
              <button id="post-menu-btn-{{ $post->id }}" class="text-gray-500 hover:text-gray-700 focus:outline-none" onclick="toggleMenu({{ $post->id }})" aria-label="Post options">
                <i class="fa-solid fa-ellipsis-vertical"></i>
              </button>
              <div id="post-menu-{{ $post->id }}" class="hidden absolute right-0 mt-2 w-28 bg-white border border-gray-300 rounded shadow-md z-10">
                {{-- Unhide --}}
                <form action="{{ route('posts.toggleHidden', $post->id) }}" method="POST">
                  @csrf
                  <button type="submit" class="block w-full text-left px-4 py-2 hover:bg-gray-100 text-sm text-gray-700">Unhide</button>
                </form>
                {{-- Delete --}}
                <form action="{{ route('posts.destroy', $post->id) }}" method="POST" onsubmit="return confirm('Are you sure to delete this post?');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="block w-full text-left px-4 py-2 hover:bg-gray-100 text-sm text-red-600">Delete</button>
                </form>
              </div>
            </div>
          </div>
        </div>

        <div class="mb-4 text-base text-gray-900 leading-relaxed">{{ $post->content }}</div>

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
