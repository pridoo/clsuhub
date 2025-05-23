@extends('user.profile.profile')

@section('title', 'Account Info')

@section('tab-content')
<form action="{{ route('profile.photo.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8" id="profileForm">
    @csrf
    <div class="grid grid-cols-12 gap-10 items-start">

        <div class="col-span-3 flex flex-col items-center space-y-4">
            <div class="w-full max-w-xs relative">
                @php
                    $user = auth()->user();
                    $profilePhoto = $user->profile_photo
                        ? asset('storage/' . $user->profile_photo)
                        : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=random';
                @endphp

                <img
                    id="profileImagePreview"
                    class="w-32 h-32 rounded-full object-cover border border-gray-300 mx-auto"
                    src="{{ $profilePhoto }}"
                    alt="Profile Photo"
                />
            </div>

            <label for="profile_photo"
                   class="cursor-pointer mt-2 px-4 py-1 bg-gray-700 text-white rounded hover:bg-gray-600 text-sm">
                Change Photo
            </label>
            <input id="profile_photo" name="profile_photo" type="file" accept="image/*" class="hidden" onchange="previewImage(event)">

            <button type="submit" class="mt-3 border border-gray-300 rounded px-6 py-2 text-sm hover:bg-gray-100 w-full max-w-xs mx-auto">
                Save Photo
            </button>
        </div>

        <div class="col-span-9">
            <h2 class="flex items-center space-x-4 text-2xl font-semibold mb-4">
                <span>{{ $user->name ?? 'Full Name' }}</span>
                <span class="text-yellow-400 text-base font-light border border-yellow-400 rounded-full w-8 h-8 flex items-center justify-center">BS</span>
            </h2>
            <p class="text-gray-500 mb-2">{{ $user->email ?? 'email@example.com' }}</p>
            <p class="font-semibold mb-6">Member since: {{ $user->created_at->format('F Y') }}</p>

            <div class="bg-gray-50 p-6 rounded shadow mb-8">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-semibold text-xl">About Me</h3>
                    <button 
                        type="button"
                        id="openAboutModalBtn"
                        class="text-sm border px-4 py-2 rounded hover:bg-gray-100"
                    >
                        Edit
                    </button>
                </div>
                <div class="flex ">
                    <p class="whitespace-pre-wrap text-gray-600 text-base max-w-prose ml-6" id="aboutMeText">
                        {{ $user->about_me ?? 'Write something about yourself.' }}
                    </p>
                </div>
            </div>

            <div class="bg-gray-50 p-6 rounded shadow">
                <h3 class="font-semibold text-xl mb-8">Academic Achievements</h3>
                <div class="text-center">
                    <div class="inline-flex items-center justify-center rounded-full border-2 border-yellow-400 w-24 h-24 mx-auto mb-4">
                        <span class="text-yellow-400 text-5xl font-thin">BS</span>
                    </div>
                    <p class="inline-flex items-center text-base text-blue-600 font-medium mx-auto">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true"><path d="M16 5v2H4V5h12zm0 6v2H4v-2h12z"/></svg>
                        Bachelor of Science
                    </p>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- About Me Edit Modal -->
<div id="aboutMeModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-xl max-w-lg w-full p-6 shadow-lg relative">
        <h2 class="text-2xl font-semibold mb-4">Edit About Me</h2>

        <!-- Separate form for About Me update -->
        <form id="aboutMeForm" method="POST" action="{{ route('profile.about_me.update') }}">
            @csrf
            <textarea
                name="about_me"
                id="aboutMeTextarea"
                class="w-full border border-gray-300 rounded-md p-3 text-gray-900 resize-y min-h-[120px] focus:outline-none focus:ring-2 focus:ring-blue-600"
                maxlength="500"
                required
            >{{ $user->about_me }}</textarea>
            <p class="text-gray-500 text-sm mt-1 mb-4">Max 500 characters.</p>

            <div class="flex justify-end gap-3">
                <button
                    id="cancelAboutBtn"
                    type="button"
                    class="px-4 py-2 rounded border border-gray-300 hover:bg-gray-100"
                >Cancel</button>
                <button
                    id="saveAboutBtn"
                    type="submit"
                    class="px-6 py-2 rounded bg-blue-600 text-white hover:bg-blue-700 font-semibold"
                >Save</button>
            </div>
        </form>

        <button
            id="closeAboutModalBtn"
            type="button"
            class="absolute top-4 right-4 text-gray-500 hover:text-gray-700"
            aria-label="Close"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
    </div>
</div>

<script>
function previewImage(event) {
    const input = event.target;
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = document.getElementById('profileImagePreview');
            img.src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('aboutMeModal');
    const openBtn = document.getElementById('openAboutModalBtn');
    const closeBtn = document.getElementById('closeAboutModalBtn');
    const cancelBtn = document.getElementById('cancelAboutBtn');

    openBtn.addEventListener('click', () => {
        modal.classList.remove('hidden');
        document.getElementById('aboutMeTextarea').focus();
    });

    function closeModal() {
        modal.classList.add('hidden');
    }

    closeBtn.addEventListener('click', closeModal);
    cancelBtn.addEventListener('click', closeModal);

    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            closeModal();
        }
    });
});
</script>
@endsection
