// public/js/post-interactions.js

document.addEventListener('DOMContentLoaded', () => {

  // Toggle menu visibility
  window.toggleMenu = function(postId) {
    const menu = document.getElementById(`post-menu-${postId}`);
    if (menu) menu.classList.toggle('hidden');
  };

  // Close menu if clicking outside
  window.addEventListener('click', function(e) {
    document.querySelectorAll('[id^="post-menu-"]').forEach(menu => {
      const btn = menu.previousElementSibling;
      if (!menu.contains(e.target) && !btn.contains(e.target)) {
        menu.classList.add('hidden');
      }
    });
  });

  // Toggle comment form visibility
  window.toggleCommentForm = function(postId) {
    const form = document.getElementById(`comment-form-${postId}`);
    if (form) form.classList.toggle('hidden');
  };

  // Open edit modal
  window.openEditModal = function(postId, content) {
    const modal = document.getElementById('editPostModal');
    const contentEl = document.getElementById('editPostContent');
    const postIdInput = document.getElementById('editPostId');
    const mediaPreview = document.getElementById('editMediaPreview');
    const mediaInput = document.getElementById('editMediaUpload');

    if (!modal || !contentEl || !postIdInput) return;

    contentEl.value = content;
    postIdInput.value = postId;

    mediaPreview.innerHTML = '';
    mediaPreview.classList.add('hidden');
    mediaInput.value = '';

    modal.classList.remove('hidden');
  };

  // Close edit modal buttons
  document.getElementById('closeEditModalBtn')?.addEventListener('click', () => {
    document.getElementById('editPostModal')?.classList.add('hidden');
  });
  document.getElementById('editCancelBtn')?.addEventListener('click', () => {
    document.getElementById('editPostModal')?.classList.add('hidden');
  });

  // Media preview in edit modal
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

  // Like button logic
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
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
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

  // Dummy share function
  window.sharePost = function(postId) {
    alert('Implement share functionality for post id ' + postId);
  };

});
