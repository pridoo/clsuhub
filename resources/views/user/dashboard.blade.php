@extends('layouts.main')

@section('title', 'Dashboard')

@section('content')
<section class="bg-white rounded-xl p-6 shadow-sm border border-gray-300 text-gray-900 max-w-none w-[800px] mx-auto">
  <div
    id="openModalBtn"
    tabindex="0"
    class="flex items-center gap-3 bg-gray-100 rounded-full px-4 py-3 text-gray-500 cursor-text select-none"
    role="button"
    aria-label="Create post"
  >
    @php
      $user = auth()->user();
      $avatarUrl = $user && $user->profile_photo_url
          ? $user->profile_photo_url
          : 'https://ui-avatars.com/api/?name=' . urlencode($user->name ?? 'User') . '&background=random&size=64&rounded=true&color=fff';
    @endphp
    <img src="{{ $avatarUrl }}" alt="{{ $user->name ?? 'User' }} Avatar" class="w-10 h-10 rounded-full object-cover" />
    What's on your mind, {{ $user->name ?? 'Alfred' }}?
  </div>
</section>

@include('user.modal.create-posts')

<!-- Success and Error Messages -->
@if(session('success'))
  <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 max-w-none w-[800px] mx-auto">
    {{ session('success') }}
  </div>
@endif

@if ($errors->any())
  <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 max-w-none w-[800px] mx-auto">
    <ul class="list-disc list-inside">
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif

<!-- Tabs -->
<section
  id="tabs"
  class="flex justify-center gap-10 font-semibold pt-4 border-b border-gray-300 mb-6 text-gray-900 max-w-none w-[800px] mx-auto cursor-pointer select-none"
>
  <div data-filter="public" class="pb-3 border-b-4 border-blue-600 text-blue-600">PUBLIC</div>
  <div data-filter="department" class="pb-3 border-b-4 border-transparent hover:text-gray-600 hover:border-gray-600">DEPARTMENT</div>
  {{-- Trending tab removed --}}
</section>

<!-- Posts -->
<section
  id="postsContainer"
  class="flex flex-col gap-7 justify-center max-w-none w-[800px] mx-auto px-4 transition-transform duration-400 ease-in-out opacity-100"
>
  @foreach ($posts as $post)
    @if(in_array($post->category, ['public', 'department']))
      <div class="post-item" data-category="{{ $post->category }}">
        <article class="bg-white rounded-xl p-6 shadow-sm border border-gray-300 text-gray-900 mb-8">

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

            <div class="ml-auto flex items-center gap-2">
              <div class="text-xs text-gray-400">{{ $post->created_at->diffForHumans() }}</div>

              @if(auth()->id() === $post->user_id)
                <div class="relative">
                  <button id="post-menu-btn-{{ $post->id }}" class="text-gray-500 hover:text-gray-700 focus:outline-none" onclick="toggleMenu({{ $post->id }})" aria-label="Post options">
                    <i class="fa-solid fa-ellipsis-vertical"></i>
                  </button>

                  <div id="post-menu-{{ $post->id }}" class="hidden absolute right-0 mt-2 w-32 bg-white border border-gray-300 rounded shadow-md z-10">
                    <form action="{{ route('posts.toggleHidden', $post->id) }}" method="POST">
                      @csrf
                      <button type="submit" class="flex items-center gap-2 w-full text-left px-4 py-2 hover:bg-gray-100 text-sm text-gray-700">
                        <i class="fa-solid {{ $post->hidden ? 'fa-eye' : 'fa-eye-slash' }}"></i>
                        {{ $post->hidden ? 'Unhide' : 'Hide' }}
                      </button>
                    </form>
                    <button
                      type="button"
                      class="flex items-center gap-2 w-full text-left px-4 py-2 hover:bg-gray-100 text-sm text-blue-600 cursor-pointer"
                      onclick="openEditModal({{ $post->id }}, `{{ addslashes($post->content) }}`)"
                    >
                      <i class="fa-solid fa-pen-to-square"></i> Edit
                    </button>
                    <form action="{{ route('posts.destroy', $post->id) }}" method="POST" onsubmit="return confirm('Are you sure to delete this post?');">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="flex items-center gap-2 w-full text-left px-4 py-2 hover:bg-gray-100 text-sm text-red-600">
                        <i class="fa-solid fa-trash"></i> Delete
                      </button>
                    </form>
                  </div>
                </div>
              @endif

              @include('user.modal.edit-modal')
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
                  <a href="{{ asset('storage/' . $media->file_path) }}" class="text-blue-600 underline" target="_blank">Download Attachment</a>
                @endif
              @endforeach
            </div>
          @endif

          {{-- Action buttons --}}
          <div class="flex gap-8 text-sm text-gray-600 mb-2">
            @php $userLiked = $post->likedByUser(auth()->user()->id); @endphp
            <button type="button" class="flex items-center gap-2 star-button hover:text-gray-800" data-post-id="{{ $post->id }}" aria-pressed="{{ $userLiked ? 'true' : 'false' }}">
              <i class="fa-solid fa-star {{ $userLiked ? 'text-yellow-400' : 'text-gray-400' }}"></i>
              Star (<span class="likes-count">{{ $post->likes_count }}</span>)
            </button>
            <button class="flex items-center gap-2 hover:text-gray-800" onclick="toggleCommentForm({{ $post->id }})" type="button">
              <i class="fa-solid fa-comment"></i> Comment ({{ $post->comments_count }})
            </button>
            <form action="{{ route('posts.repost', $post) }}" method="POST" class="inline repost-form">
              @csrf
              <button type="submit" class="flex items-center gap-2 hover:text-gray-800">
                <i class="fa-solid fa-repeat"></i> Repost ({{ $post->reposts_count }})
              </button>
            </form>
            <button class="flex items-center gap-2 hover:text-gray-800" onclick="sharePost({{ $post->id }})" type="button">
              <i class="fa-solid fa-share-nodes"></i> Share
            </button>
          </div>

          {{-- Main comment form --}}
          <div id="comment-form-{{ $post->id }}" class="hidden mb-4">
            <form action="{{ route('posts.comment', $post) }}" method="POST">
              @csrf
              <textarea name="comment" rows="3" class="w-full p-2 border border-gray-300 rounded" placeholder="Write your comment..." required></textarea>
              <button type="submit" class="mt-2 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Post Comment</button>
            </form>
          </div>

          {{-- Comments and nested replies --}}
          @if ($post->comments->count() > 0)
            <div id="comments-container-{{ $post->id }}" class="border-t border-gray-200 pt-4 mt-4 max-h-48 overflow-y-auto">
              @foreach ($post->comments->whereNull('parent_id') as $comment)
                <div class="mb-3" id="comment-{{ $comment->id }}">
                  <div class="font-semibold">{{ $comment->user->name }}</div>
                  <div class="text-sm text-gray-700">{{ $comment->comment }}</div>
                  <div class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</div>

                  <button type="button" class="text-blue-600 text-sm mt-1" onclick="toggleReplyForm({{ $comment->id }})">Reply</button>

                  <div id="reply-form-{{ $comment->id }}" class="hidden mt-2">
                    <textarea rows="2" class="w-full p-2 border border-gray-300 rounded" placeholder="Write your reply..."></textarea>
                    <button type="button" class="mt-1 px-3 py-1 bg-blue-600 text-white rounded" onclick="submitReply({{ $post->id }}, {{ $comment->id }})">Submit Reply</button>
                  </div>

                  {{-- Replies --}}
                  @foreach ($comment->replies as $reply)
                    <div class="ml-6 mt-2 border-l border-gray-300 pl-3" id="comment-{{ $reply->id }}">
                      <div class="font-semibold">{{ $reply->user->name }}</div>
                      <div class="text-sm text-gray-700">{{ $reply->comment }}</div>
                      <div class="text-xs text-gray-500">{{ $reply->created_at->diffForHumans() }}</div>
                      <button type="button" class="text-blue-600 text-sm mt-1" onclick="toggleReplyForm({{ $reply->id }})">Reply</button>
                      <div id="reply-form-{{ $reply->id }}" class="hidden mt-2">
                        <textarea rows="2" class="w-full p-2 border border-gray-300 rounded" placeholder="Write your reply..."></textarea>
                        <button type="button" class="mt-1 px-3 py-1 bg-blue-600 text-white rounded" onclick="submitReply({{ $post->id }}, {{ $reply->id }})">Submit Reply</button>
                      </div>
                    </div>
                  @endforeach
                </div>
              @endforeach
            </div>
          @endif

        </article>
      </div>
    @endif
  @endforeach
</section>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const tabs = document.querySelectorAll('#tabs > div');
  const postsContainer = document.getElementById('postsContainer');
  const postItems = Array.from(postsContainer.querySelectorAll('.post-item'));

  let currentFilter = 'public';
  filterPosts(currentFilter);

  tabs.forEach(tab => {
    tab.addEventListener('click', () => {
      const filter = tab.getAttribute('data-filter');
      if (filter === currentFilter) return;

      postsContainer.style.transition = 'transform 0.4s ease, opacity 0.4s ease';
      postsContainer.style.transform = 'translateX(-100%)';
      postsContainer.style.opacity = '0';

      setTimeout(() => {
        filterPosts(filter);
        postsContainer.style.transition = 'none';
        postsContainer.style.transform = 'translateX(100%)';
        postsContainer.offsetHeight;
        postsContainer.style.transition = 'transform 0.4s ease, opacity 0.4s ease';
        postsContainer.style.transform = 'translateX(0)';
        postsContainer.style.opacity = '1';

        currentFilter = filter;
        tabs.forEach(t => {
          t.classList.remove('border-blue-600', 'text-blue-600');
          t.classList.add('border-transparent', 'hover:text-gray-600', 'hover:border-gray-600');
        });
        tab.classList.add('border-blue-600', 'text-blue-600');
        tab.classList.remove('border-transparent', 'hover:text-gray-600', 'hover:border-gray-600');
      }, 400);
    });
  });

  function filterPosts(filter) {
    postItems.forEach(item => {
      item.style.display = item.getAttribute('data-category') === filter ? 'block' : 'none';
    });
  }

  window.toggleMenu = function(postId) {
    const menu = document.getElementById(`post-menu-${postId}`);
    if(menu) menu.classList.toggle('hidden');
  };

  window.addEventListener('click', function(e) {
    document.querySelectorAll('[id^="post-menu-"]').forEach(menu => {
      const btn = menu.previousElementSibling;
      if (!menu.contains(e.target) && !btn.contains(e.target)) {
        menu.classList.add('hidden');
      }
    });
  });

  window.toggleCommentForm = function(postId) {
    const form = document.getElementById(`comment-form-${postId}`);
    if(form) form.classList.toggle('hidden');
  };

  window.openEditModal = function(postId, content) {
    const modal = document.getElementById('editPostModal');
    const contentEl = document.getElementById('editPostContent');
    const postIdInput = document.getElementById('editPostId');
    const mediaPreview = document.getElementById('editMediaPreview');
    const mediaInput = document.getElementById('editMediaUpload');

    if(!modal || !contentEl || !postIdInput) return;

    contentEl.value = content;
    postIdInput.value = postId;

    mediaPreview.innerHTML = '';
    mediaPreview.classList.add('hidden');
    mediaInput.value = '';

    modal.classList.remove('hidden');
  };

  document.getElementById('closeEditModalBtn')?.addEventListener('click', () => {
    document.getElementById('editPostModal')?.classList.add('hidden');
  });
  document.getElementById('editCancelBtn')?.addEventListener('click', () => {
    document.getElementById('editPostModal')?.classList.add('hidden');
  });

  document.getElementById('editMediaUpload')?.addEventListener('change', function() {
    const preview = document.getElementById('editMediaPreview');
    if (!preview) return;

    if (this.files.length === 0) {
      preview.innerHTML = '';
      preview.classList.add('hidden');
      return;
    }
    const file = this.files[0];
    const fileType = file.type;
    const reader = new FileReader();

    reader.onload = function(e) {
      let html = '';
      if (fileType.startsWith('image/')) {
        html = `<img src="${e.target.result}" alt="Preview" class="w-full max-h-64 object-contain" />`;
      } else if (fileType.startsWith('video/')) {
        html = `<video controls class="w-full max-h-64"><source src="${e.target.result}" type="${fileType}">Your browser does not support the video tag.</video>`;
      } else {
        html = `<p class="text-sm text-red-600">Unsupported file type</p>`;
      }
      preview.innerHTML = html;
      preview.classList.remove('hidden');
    };
    reader.readAsDataURL(file);
  });

  // AJAX for Star button (like/unlike)
  document.querySelectorAll('.star-button').forEach(button => {
    button.addEventListener('click', async () => {
      if (button.disabled) return;
      button.disabled = true;

      const postId = button.dataset.postId;
      const starIcon = button.querySelector('i.fa-star');
      const likesCountSpan = button.querySelector('.likes-count');

      try {
        const response = await fetch(`/posts/${postId}/like`, {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
          },
          body: JSON.stringify({})
        });

        if (!response.ok) throw new Error('Network response not ok');

        const data = await response.json();

        if (data.liked) {
          starIcon.classList.add('text-yellow-400');
          starIcon.classList.remove('text-gray-400');
          button.setAttribute('aria-pressed', 'true');
        } else {
          starIcon.classList.remove('text-yellow-400');
          starIcon.classList.add('text-gray-400');
          button.setAttribute('aria-pressed', 'false');
        }

        likesCountSpan.textContent = data.likes_count;

      } catch (error) {
        alert('Failed to update like. Try again.');
        console.error(error);
      } finally {
        button.disabled = false;
      }
    });
  });

  // AJAX for Repost Submission
  document.querySelectorAll('form.repost-form').forEach(form => {
    form.addEventListener('submit', async (e) => {
      e.preventDefault();

      const button = form.querySelector('button[type="submit"]');
      if (!button) return;
      if (button.disabled) return;
      button.disabled = true;
      button.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Reposting...';

      const action = form.getAttribute('action');

      try {
        const response = await fetch(action, {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
          }
        });

        if (!response.ok) throw new Error('Network response not ok');

        const data = await response.json();

        button.innerHTML = `<i class="fa-solid fa-repeat"></i> Repost (${data.reposts_count})`;

        Swal.fire({
          icon: 'success',
          title: 'Reposted successfully!',
          timer: 2000,
          showConfirmButton: false
        });

      } catch (error) {
        Swal.fire({
          icon: 'error',
          title: 'Failed to repost',
          text: error.message || 'Please try again later.'
        });
        button.innerHTML = `<i class="fa-solid fa-repeat"></i> Repost`;
      } finally {
        button.disabled = false;
      }
    });
  });

  window.sharePost = function(postId) {
    alert('Implement share functionality for post id ' + postId);
  };

  // Comment Reply AJAX and toggle form
  window.toggleReplyForm = function(commentId) {
    const form = document.getElementById(`reply-form-${commentId}`);
    if(form) form.classList.toggle('hidden');
  };

  window.submitReply = async function(postId, parentId) {
    let textarea;
    if(parentId) {
      textarea = document.querySelector(`#reply-form-${parentId} textarea`);
    } else {
      textarea = document.getElementById(`new-comment-text-${postId}`);
    }
    if(!textarea) return;

    const comment = textarea.value.trim();
    if(comment.length === 0) {
      Swal.fire('Error', 'Please write something.', 'error');
      return;
    }

    try {
      const response = await fetch(`/posts/${postId}/comment`, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
          'Content-Type': 'application/json',
          'Accept': 'application/json'
        },
        body: JSON.stringify({ comment, parent_id: parentId })
      });

      if(!response.ok) throw new Error('Network error');

      const data = await response.json();

      if(data.success) {
        textarea.value = '';

        const html = createCommentHtml(data.comment);

        if(parentId) {
          // Insert reply inside parent comment div
          const parentDiv = document.getElementById(`comment-${parentId}`);
          if(parentDiv) {
            parentDiv.insertAdjacentHTML('beforeend', html);
            toggleReplyForm(parentId);
          }
        } else {
          // Insert new root comment into comments container
          const commentsContainer = document.getElementById(`comments-container-${postId}`);
          if(commentsContainer) {
            commentsContainer.insertAdjacentHTML('afterbegin', html);
            toggleCommentForm(postId);
          }
        }

        Swal.fire({title: 'Success', text: 'Comment posted!', icon: 'success', timer: 2500, showConfirmButton: false});
      } else {
        Swal.fire('Error', data.message || 'Failed to post comment.', 'error');
      }
    } catch(err) {
      console.error(err);
      Swal.fire('Error', 'An error occurred. Try again.', 'error');
    }
  };

  function createCommentHtml(comment) {
    return `
      <div class="mb-3" id="comment-${comment.id}">
        <div class="font-semibold">${comment.user_name}</div>
        <div class="text-sm text-gray-700">${comment.comment}</div>
        <div class="text-xs text-gray-500">Just now</div>
        <button type="button" class="text-blue-600 text-sm mt-1" onclick="toggleReplyForm(${comment.id})">Reply</button>
        <div id="reply-form-${comment.id}" class="hidden mt-2">
          <textarea rows="2" class="w-full p-2 border border-gray-300 rounded" placeholder="Write your reply..."></textarea>
          <button type="button" class="mt-1 px-3 py-1 bg-blue-600 text-white rounded" onclick="submitReply(${comment.post_id}, ${comment.id})">Submit Reply</button>
        </div>
      </div>
    `;
  }

});
</script>

@if (session('success'))
<script>
  Swal.fire({
    icon: 'success',
    title: 'Success!',
    text: "{{ session('success') }}",
    confirmButtonColor: '#3085d6',
    timer: 3000,
    timerProgressBar: true
  });
</script>
@endif

@if ($errors->any())
<script>
  Swal.fire({
    icon: 'error',
    title: 'Error!',
    html: `{!! implode('<br>', $errors->all()) !!}`,
    confirmButtonColor: '#d33'
  });
</script>
@endif
@endsection
