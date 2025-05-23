@foreach ($comments->whereNull('parent_id') as $comment)
  <div class="mb-4" id="comment-{{ $comment->id }}">
    <div class="font-semibold">{{ $comment->user->name }}</div>
    <div class="text-sm text-gray-700 whitespace-pre-line">{{ $comment->comment }}</div>
    <div class="text-xs text-gray-500 mb-1">{{ $comment->created_at->diffForHumans() }}</div>

    <button type="button" class="text-blue-600 text-sm mb-2" onclick="toggleReplyForm({{ $comment->id }})">Reply</button>

    <div id="reply-form-{{ $comment->id }}" class="hidden mb-2">
      <textarea rows="2" class="w-full p-2 border border-gray-300 rounded" placeholder="Write your reply..."></textarea>
      <button type="button" class="mt-1 px-3 py-1 bg-blue-600 text-white rounded" onclick="submitReply({{ $postId }}, {{ $comment->id }})">Submit Reply</button>
    </div>

    @if($comment->replies->count())
      <div class="ml-8 border-l border-gray-300 pl-4">
        @foreach ($comment->replies as $reply)
          <div class="mb-3" id="comment-{{ $reply->id }}">
            <div class="flex items-center space-x-2">
              <img src="{{ $reply->user->profile_photo ? asset('storage/' . $reply->user->profile_photo) : 'https://ui-avatars.com/api/?name=' . urlencode($reply->user->name) }}" alt="{{ $reply->user->name }}" class="w-6 h-6 rounded-full object-cover border border-gray-300">
              <div class="font-semibold text-sm">{{ $reply->user->name }}</div>
            </div>
            <div class="text-sm text-gray-700 whitespace-pre-line mt-1">{{ $reply->comment }}</div>
            <div class="text-xs text-gray-500">{{ $reply->created_at->diffForHumans() }}</div>
            
            <button type="button" class="text-blue-600 text-xs mt-1" onclick="toggleReplyForm({{ $reply->id }})">Reply</button>

            <div id="reply-form-{{ $reply->id }}" class="hidden mt-1">
              <textarea rows="1" class="w-full p-1 border border-gray-300 rounded text-xs" placeholder="Write your reply..."></textarea>
              <button type="button" class="mt-1 px-2 py-0.5 bg-blue-600 text-white rounded text-xs" onclick="submitReply({{ $postId }}, {{ $reply->id }})">Submit</button>
            </div>
          </div>
        @endforeach
      </div>
    @endif
  </div>
@endforeach
