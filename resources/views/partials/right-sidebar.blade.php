<link href="{{ asset('css/right-sidebar.css') }}" rel="stylesheet" />


@php
  $user = Auth::user();
  $profilePhoto = $user->profile_photo
      ? asset('storage/' . $user->profile_photo)
      : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=random';
@endphp

<!-- Make sure Alpine.js is loaded somewhere in your layout -->
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<section class="bg-gray-900 p-6 rounded-xl text-gray-100 shadow-lg flex items-center gap-4">
  <img
    src="{{ $profilePhoto }}"
    alt="{{ $user->name }}"
    class="w-12 h-12 rounded-full object-cover border-2 border-gray-600"
  />
  <div>
    <div class="font-semibold text-lg flex items-center gap-2">
      Welcome, Alumni {{ $user->name }}! <i class="fa-solid fa-check-circle text-gray-400"></i>
    </div>
    <div class="mt-2 text-sm leading-tight">
      Feel Free to Use our ConnectED
    </div>
  </div>
</section>

<section
  x-data="{ showModal: false }"
  class="bg-gray-900 p-6 rounded-xl text-gray-100 shadow-lg mt-6"
>
  <h3 class="mb-6 text-base font-bold">ALUMNI GRADUATED</h3>

  {{-- Show first 5 alumni only in sidebar --}}
  @foreach ($alumni->take(5) as $alum)
    @php
      $alumPhoto = $alum->profile_photo
        ? asset('storage/' . $alum->profile_photo)
        : 'https://ui-avatars.com/api/?name=' . urlencode($alum->full_name) . '&background=random';
    @endphp

    <div class="flex items-center gap-3 mb-3 cursor-default">
      <img
        src="{{ $alumPhoto }}"
        alt="{{ $alum->full_name }}"
        class="w-9 h-9 rounded-full object-cover border border-gray-600"
      />
      <div class="text-sm">
        <div class="font-semibold">{{ $alum->full_name }}</div>
        <div class="text-xs text-gray-400">Batch {{ $alum->batch_year }}</div>
      </div>
    </div>
  @endforeach

  <button
    @click="showModal = true"
    class="w-full mt-1 py-2 text-gray-400 font-semibold hover:text-gray-300 cursor-pointer transition-colors"
  >
    Show more
  </button>

  <!-- Modal backdrop -->
  <div
    x-show="showModal"
    x-transition
    x-cloak
    class="fixed inset-0 bg-black bg-opacity-60 flex justify-center items-center z-50"
  >
    <!-- Modal box -->
    <div
      @click.away="showModal = false"
      class="bg-gray-900 text-gray-100 rounded-xl shadow-lg max-h-[80vh] w-[90vw] max-w-lg overflow-y-auto p-6"
      x-trap="showModal"
    >
      <h3 class="mb-6 text-lg font-bold">All Alumni Graduated</h3>

      @foreach ($alumni as $alum)
        @php
          $alumPhoto = $alum->profile_photo
            ? asset('storage/' . $alum->profile_photo)
            : 'https://ui-avatars.com/api/?name=' . urlencode($alum->full_name) . '&background=random';
        @endphp

        <div class="flex items-center gap-3 mb-3 cursor-default">
          <img
            src="{{ $alumPhoto }}"
            alt="{{ $alum->full_name }}"
            class="w-9 h-9 rounded-full object-cover border border-gray-600"
          />
          <div class="text-sm">
            <div class="font-semibold">{{ $alum->full_name }}</div>
            <div class="text-xs text-gray-400">Batch {{ $alum->batch_year }}</div>
          </div>
        </div>
      @endforeach

      <button
        @click="showModal = false"
        class="mt-4 py-2 px-4 bg-gray-700 hover:bg-gray-600 rounded-md font-semibold"
      >
        Close
      </button>
    </div>
  </div>
</section>
