@extends('user.profile.profile')

@section('title', 'My Comments')

@section('tab-content')
<div class="max-w-4xl mx-auto mt-6">

  <h2 class="text-2xl font-semibold mb-6">My Comments</h2>

  @if($comments->isEmpty())
    <p class="text-gray-600">You have not made any comments yet.</p>
  @else
    @foreach ($comments as $comment)
      <article class="bg-white rounded-xl p-4 shadow-sm border border-gray-300 text-gray-900 mb-6">

        <div class="mb-2 text-sm text-gray-500">
          Commented {{ $comment->created_at->diffForHumans() }}
        </div>

        <div class="mb-3 text-base text-gray-800">
          {{ $comment->comment }}
        </div>

        {{-- Link to the post where the comment belongs --}}
        @if($comment->post)
          <a href="{{ route('posts.show', $comment->post->id) }}" class="text-blue-600 hover:underline text-sm">
            View Post: "{{ \Illuminate\Support\Str::limit($comment->post->content, 50) }}"
          </a>
        @else
          <span class="text-gray-400 text-sm italic">Original post deleted</span>
        @endif

      </article>
    @endforeach
  @endif

</div>
@endsection
