<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Absen PKL') }}</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<body class="bg-blue-50">

    <!-- NAVBAR -->
    <!-- NAVBAR -->
    <nav class="bg-white shadow-md border-b-4 border-blue-300">
        <div class="max-w-6xl mx-auto px-4">
            <div class="flex justify-between h-16 items-center">

                <!-- Logo -->
                <a href="/" class="flex items-center gap-3">
                    <img src="{{ asset('SMKN2TASIK.png') }}" class="h-10" alt="Logo">
                    <span class="text-xl font-semibold text-blue-800">
                        {{ config('app.name', 'Absen PKL') }}
                    </span>
                </a>

                <!-- Hamburger menu button (mobile) -->
                <div class="md:hidden">
                    <button id="menu-btn" class="text-blue-800 focus:outline-none">
                        <!-- icon burger -->
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>

                <!-- NAV LINKS -->
                <div id="menu" class="hidden md:flex space-x-6">
                    <a href="{{ route('attendance.scan') }}"
                        class="text-blue-800 font-medium hover:text-blue-500 transition">Scan</a>
                    <a href="{{ route('attendance.history') }}"
                        class="text-blue-800 font-medium hover:text-blue-500 transition">History</a>
                    <a href="{{ route('attendance.settings') }}"
                        class="text-blue-800 font-medium hover:text-blue-500 transition">Settings</a>
                    <a href="{{ route('attendance.user') }}"
                        class="text-blue-800 font-medium hover:text-blue-500 transition">User</a>
                </div>

            </div>

            <!-- Mobile menu (hidden by default) -->
            <div id="mobile-menu" class="md:hidden hidden flex-col space-y-2 mt-2 px-2 pb-4">
                <a href="{{ route('attendance.scan') }}"
                    class="block text-blue-800 font-medium hover:text-blue-500 transition">Scan</a>
                <a href="{{ route('attendance.history') }}"
                    class="block text-blue-800 font-medium hover:text-blue-500 transition">History</a>
                <a href="{{ route('attendance.settings') }}"
                    class="block text-blue-800 font-medium hover:text-blue-500 transition">Settings</a>
                <a href="{{ route('attendance.user') }}"
                    class="block text-blue-800 font-medium hover:text-blue-500 transition">User</a>
            </div>

        </div>
    </nav>




    <!-- CONTENT -->
    <main class="max-w-4xl mx-auto mt-8 px-4">
        <div class="bg-white p-6 rounded-xl shadow-lg border-t-4 border-blue-400">
            @yield('content')
        </div>
    </main>
    <script>
        const btn = document.getElementById('menu-btn');
        const menu = document.getElementById('mobile-menu');

        btn.addEventListener('click', () => {
            menu.classList.toggle('hidden');
        });
    </script>
    <!-- SweetAlert -->
    <script>
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                confirmButtonColor: '#3b82f6'
            });
        @endif

        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: "{{ session('error') }}",
                confirmButtonColor: '#ef4444'
            });
        @endif
    </script>

</body>

</html>
