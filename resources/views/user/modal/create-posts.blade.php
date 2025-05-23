<div id="createPostModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden z-50">
  <div class="bg-white rounded-xl p-6 shadow-lg w-[500px] max-w-full relative">
    <!-- Close button -->
    <button
      id="closeModalBtn"
      class="absolute top-4 right-4 text-gray-600 hover:text-gray-900 text-2xl font-bold"
      aria-label="Close modal"
      type="button"
    >&times;</button>

    <form
      id="modalPostForm"
      method="POST"
      action="{{ route('posts.store') }}"
      enctype="multipart/form-data"
      class="space-y-6"
    >
      @csrf

      <!-- Step 1: Post Content + Media -->
      <div id="step1">
        <div class="flex items-center gap-3 mb-4">
          <img
            src="{{ $avatarUrl ?? 'https://ui-avatars.com/api/?name=User&background=random&size=64&rounded=true&color=fff' }}"
            alt="{{ $user->name ?? 'User' }} Avatar"
            class="w-10 h-10 rounded-full object-cover"
          />
          <span class="font-semibold text-gray-900">{{ $user->name ?? 'Alfred' }}</span>
        </div>

        <textarea
          id="postContent"
          name="content"
          placeholder="What's on your mind, {{ $user->name ?? 'Alfred' }}?"
          class="w-full bg-gray-100 rounded-xl px-4 py-3 text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-600 resize-none"
          rows="4"
          required>{{ old('content') }}</textarea>
        @error('content')
          <p class="text-red-600 text-sm">{{ $message }}</p>
        @enderror

        <!-- Media upload button -->
        <div class="flex items-center mt-4 gap-3">
          <label
            for="mediaUpload"
            class="flex items-center gap-2 cursor-pointer text-blue-600 hover:text-blue-800"
            aria-label="Add photo or video"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2z" />
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M8 11l2 2 4-4" />
            </svg>
            <span>Add Photo/Video</span>
          </label>
          <input
            type="file"
            id="mediaUpload"
            name="media"
            accept="image/*,video/*"
            class="hidden"
          />
        </div>

        <!-- Media preview -->
        <div id="mediaPreview" class="mt-4 rounded-lg overflow-hidden hidden border border-gray-300">
          <!-- Preview will appear here -->
        </div>

        <!-- Next button -->
        <div class="flex justify-end mt-6">
          <button
            type="button"
            id="nextBtn"
            disabled
            class="bg-gray-400 text-white font-bold py-2 px-6 rounded-md cursor-not-allowed"
          >
            Next
          </button>
        </div>
      </div>

      <!-- Step 2: Privacy selection -->
      <div id="step2" class="hidden">
        <h3 class="text-lg font-semibold mb-4">Choose Privacy</h3>
        <select
          name="privacy"
          id="privacySelect"
          class="w-full border rounded px-3 py-2 text-gray-900 text-sm focus:outline-none focus:ring-2 focus:ring-blue-600"
          required
        >
          <option value="public" selected>🌐 Public</option>
          <option value="department">🏢 Department Only</option>
        </select>

        <div class="flex justify-between mt-6">
          <button
            type="button"
            id="backBtn"
            class="bg-gray-200 text-gray-700 font-semibold py-2 px-6 rounded-md hover:bg-gray-300"
          >
            Back
          </button>
          <button
            type="submit"
            id="postBtn"
            disabled
            class="bg-gray-400 text-white font-bold py-2 px-6 rounded-md cursor-not-allowed"
          >
            Post
          </button>
        </div>
      </div>
    </form>
  </div>
</div>
