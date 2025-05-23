<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>@yield('title', 'Dashboard')</title>

  <script src="https://cdn.tailwindcss.com"></script>
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
  />

  <!-- SweetAlert2 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet" />
</head>
<body class="font-sans bg-gray-50 text-gray-900 min-h-screen flex flex-col">

  <div class="flex-1 flex w-full h-screen gap-6 p-6">
    <!-- Sidebar Left -->
    <aside
      class="w-[250px] flex flex-col gap-4 bg-gradient-to-b from-gray-800 to-gray-900 text-gray-100 rounded-xl p-6 shadow-lg h-screen"
    >
      <a href="{{ url('admin/dashboard') }}" class="font-semibold px-4 py-3 rounded-lg flex items-center gap-3 transition-colors duration-200 hover:bg-gray-700 hover:text-white">
        <i class="fa-solid fa-house"></i> Home
      </a>


      <!-- User Info -->
      <div class="mt-auto pt-6 border-t border-gray-700 text-center text-gray-300">
        <img
          src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=random"
          alt="{{ Auth::user()->name }}"
          class="w-16 h-16 rounded-full object-cover mb-3 border-2 border-gray-600 mx-auto"
        />
        <div class="font-bold text-lg">{{ Auth::user()->name }}</div>
        <div class="text-gray-400 text-sm">{{ Auth::user()->email }}</div>

        <form id="logoutForm" method="POST" action="{{ route('logout') }}">
          @csrf
          <button
            type="submit"
            id="logoutBtn"
            class="block w-full mt-3 py-3 px-4 bg-red-600 text-white font-bold rounded-md cursor-pointer transition-colors duration-300 hover:bg-red-700"
          >
            Logout
          </button>
        </form>
      </div>
    </aside>

    <!-- Main Content -->
    <main
      class="flex-1 flex flex-col gap-6 bg-white rounded-xl p-6 shadow-md border border-gray-300 overflow-y-auto h-screen"
    >
      @yield('content')
    </main>
  </div>

  <!-- SweetAlert2 JS -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script>
    const logoutForm = document.getElementById('logoutForm');
    if (logoutForm) {
      logoutForm.addEventListener('submit', function(e) {
        e.preventDefault();
        Swal.fire({
          title: 'Are you sure?',
          text: "You will be logged out of your account.",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#3085d6',
          confirmButtonText: 'Yes, logout',
          cancelButtonText: 'Cancel',
          reverseButtons: true,
          customClass: {
            popup: 'rounded-lg shadow-xl'
          }
        }).then((result) => {
          if (result.isConfirmed) {
            logoutForm.submit();
          }
        });
      });
    }
  </script>

</body>
</html>
