@foreach ($posts as $post)
<article class="bg-white rounded-xl p-6 shadow-sm border border-gray-300 text-gray-900 mb-8">

  {{-- Post header --}}
  <div class="flex items-center gap-4 mb-2 relative">
    <img
      class="w-14 h-14 rounded-full object-cover border border-gray-400"
      src="{{ $post->user->profile_photo ? asset('storage/' . $post->user->profile_photo) : 'https://ui-avatars.com/api/?name=' . urlencode($post->user->name) }}"
      alt="{{ $post->user->name }}"
    />
    <div>
      <div class="font-bold text-gray-800 text-lg">{{ $post->user->name }}</div>
      <div class="text-xs text-gray-500">
        {{ $post->created_at->format('h:i A') }} ·
        {{ ucfirst($post->privacy ?? 'Public') }}
      </div>
    </div>
    <div class="ml-auto flex items-center gap-2">
      <div class="text-xs text-gray-400">{{ $post->created_at->diffForHumans() }}</div>
      @if(auth()->id() === $post->user_id)
        {{-- Your existing 3-dots menu here --}}
        @include('user.modal.edit-modal')
      @endif
    </div>
  </div>

  {{-- Post content --}}
  <div class="mb-4 text-base text-gray-900 leading-relaxed">{{ $post->content }}</div>

  {{-- Media --}}
  {{-- Your existing media code here --}}

  {{-- Action buttons --}}
  {{-- Your existing action buttons code here --}}

  {{-- Comment form --}}
  <div id="comment-form-{{ $post->id }}" class="hidden mb-4">
    <form action="{{ route('posts.comment', $post) }}" method="POST">
      @csrf
      <textarea name="comment" rows="3" class="w-full p-2 border border-gray-300 rounded" placeholder="Write your comment..." required></textarea>
      <button type="submit" class="mt-2 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Post Comment</button>
    </form>
  </div>

  {{-- Comments and replies --}}
  @if ($post->comments->count() > 0)
  <div class="border-t border-gray-200 pt-4 mt-4 max-h-48 overflow-y-auto">
    @foreach ($post->comments->where('parent_id', null) as $comment)
      @include('user.partials.comment', ['comment' => $comment])
    @endforeach
  </div>
  @endif

</article>
@endforeach
