<div class="mb-3" id="comment-{{ $comment->id }}">
  <div class="font-semibold">{{ $comment->user->name }}</div>
  <div class="text-sm text-gray-700">{{ $comment->comment }}</div>
  <div class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</div>
  <button type="button" class="text-blue-600 text-sm mt-1" onclick="toggleReplyForm({{ $comment->id }})">Reply</button>
  <div id="reply-form-{{ $comment->id }}" class="hidden mt-2">
    <textarea rows="2" class="w-full p-2 border border-gray-300 rounded" placeholder="Write your reply..."></textarea>
    <button type="button" class="mt-1 px-3 py-1 bg-blue-600 text-white rounded" onclick="submitReply({{ $comment->post_id }}, {{ $comment->id }})">Submit Reply</button>
  </div>

  {{-- Replies displayed inline --}}
  @if($comment->replies->count())
  <div class="flex flex-wrap gap-4 mt-3">
    @foreach ($comment->replies as $reply)
      <div class="flex items-center space-x-2 bg-gray-100 p-2 rounded shadow-sm max-w-xs" id="comment-{{ $reply->id }}">
        <img src="{{ $reply->user->profile_photo ? asset('storage/' . $reply->user->profile_photo) : 'https://ui-avatars.com/api/?name=' . urlencode($reply->user->name) }}" alt="{{ $reply->user->name }}" class="w-8 h-8 rounded-full object-cover border border-gray-300">
        <div class="text-sm text-gray-700 truncate max-w-[150px]">{{ $reply->comment }}</div>
        <button type="button" class="text-blue-600 text-xs" onclick="toggleReplyForm({{ $reply->id }})">Reply</button>
        <div id="reply-form-{{ $reply->id }}" class="hidden mt-1 w-full">
          <textarea rows="1" class="w-full p-1 border border-gray-300 rounded text-xs" placeholder="Write your reply..."></textarea>
          <button type="button" class="mt-1 px-2 py-0.5 bg-blue-600 text-white rounded text-xs" onclick="submitReply({{ $comment->post_id }}, {{ $reply->id }})">Submit</button>
        </div>
      </div>
    @endforeach
  </div>
  @endif
</div>
