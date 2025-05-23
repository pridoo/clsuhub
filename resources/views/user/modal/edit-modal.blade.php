<div id="editPostModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden z-50">
  <div class="bg-white rounded-xl p-6 shadow-lg w-[500px] max-w-full relative">
    <!-- Close button -->
    <button
      id="closeEditModalBtn"
      class="absolute top-4 right-4 text-gray-600 hover:text-gray-900 text-2xl font-bold"
      aria-label="Close modal"
      type="button"
    >&times;</button>

    <h2 class="text-xl font-semibold mb-6">Edit Post</h2>

    <form
      id="editPostForm"
      method="POST"
      action="{{ route('posts.update') }}"
      enctype="multipart/form-data"
      class="space-y-6"
    >
      @csrf
      @method('PUT')

      <input type="hidden" name="post_id" id="editPostId" />

      <!-- Post content -->
      <textarea
        id="editPostContent"
        name="content"
        placeholder="Update your post..."
        class="w-full bg-gray-100 rounded-xl px-4 py-3 text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-600 resize-none"
        rows="4"
        required
      >{{ old('content') }}</textarea>
      @error('content')
        <p class="text-red-600 text-sm">{{ $message }}</p>
      @enderror

      <!-- Media upload -->
      <div class="flex items-center gap-3">
        <label
          for="editMediaUpload"
          class="flex items-center gap-2 cursor-pointer text-blue-600 hover:text-blue-800"
          aria-label="Change photo or video"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M8 11l2 2 4-4" />
          </svg>
          <span>Change Photo/Video</span>
        </label>
        <input
          type="file"
          id="editMediaUpload"
          name="media"
          accept="image/*,video/*"
          class="hidden"
        />
      </div>

      <!-- Media preview -->
      <div
        id="editMediaPreview"
        class="mt-4 rounded-lg overflow-hidden hidden border border-gray-300"
        aria-live="polite"
      >
        <!-- Preview will appear here -->
      </div>

      <!-- Privacy selection -->
      <div>
        <label for="editPrivacySelect" class="block mb-1 font-medium text-gray-700">Choose Privacy</label>
        <select
          name="privacy"
          id="editPrivacySelect"
          class="w-full border rounded px-3 py-2 text-gray-900 text-sm focus:outline-none focus:ring-2 focus:ring-blue-600"
          required
        >
          <option value="public">🌐 Public</option>
          <option value="department">🏢 Department Only</option>
        </select>
      </div>

      <!-- Buttons -->
      <div class="flex justify-end gap-3">
        <button
          type="button"
          id="editCancelBtn"
          class="px-6 py-2 bg-gray-300 rounded-md font-semibold hover:bg-gray-400"
        >
          Cancel
        </button>
        <button
          type="submit"
          id="editSaveBtn"
          class="px-6 py-2 bg-blue-600 text-white rounded-md font-bold hover:bg-blue-700"
        >
          Save
        </button>
      </div>
    </form>
  </div>
</div>
