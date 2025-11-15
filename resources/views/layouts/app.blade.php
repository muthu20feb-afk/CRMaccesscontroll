<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dev')</title>

    {{-- Tailwind & App CSS/JS --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- @vite(['resources/css/datatable.css', 'resources/js/datatable.js']) --}}

    {{-- DataTables CSS --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    {{-- Alpine.js --}}
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    {{-- @vite('resources/js/main.js') --}}
</head>
<body class="bg-gray-100 dark:bg-gray-900">

    {{-- NAVBAR --}}
    <nav x-data="{ open: false }"
         class="sticky top-0 z-50 bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700 sm:ml-64">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between h-16 items-center">
            <div class="flex-shrink-0 flex items-center">
                <a href="/" class="text-xl font-bold text-gray-900 dark:text-white"></a>
            </div>

            {{-- User Dropdown --}}
            @if(Auth::check())
            <div class="relative" id="userDropdown">
                <!-- Profile Button -->
                <button id="userDropdownBtn"
                    class="flex items-center space-x-3 focus:outline-none group">
                    <div
                        class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-500 text-white flex items-center justify-center font-semibold text-lg shadow-md group-hover:scale-105 transition-transform">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <span class="hidden sm:block text-gray-800 dark:text-gray-100 font-medium text-sm">
                        {{ Auth::user()->name }}
                    </span>
                    <svg class="w-4 h-4 text-gray-600 dark:text-gray-300 group-hover:rotate-180 transition-transform"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <!-- Dropdown Menu -->
                <div id="userDropdownMenu"
                    class="hidden absolute right-0 mt-3 w-64 bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 z-50 overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center space-x-3">
                            <div
                                class="w-12 h-12 rounded-full bg-gradient-to-br from-indigo-500 to-purple-500 text-white flex items-center justify-center text-xl font-bold shadow-sm">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <div>
                                <div class="text-gray-800 dark:text-gray-100 font-semibold text-sm">
                                    {{ Auth::user()->name }}
                                </div>
                                <div class="text-gray-500 dark:text-gray-400 text-xs mt-0.5">
                                    Roles:
                                    @foreach(Auth::user()->roles as $role)
                                        <span
                                            class="inline-block bg-blue-100 text-blue-800 text-[11px] font-semibold px-2 py-0.5 rounded-full mr-1 dark:bg-blue-900 dark:text-blue-300">
                                            {{ ucfirst($role->name) }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('logout') }}" class="py-2">
                        @csrf
                        <button type="submit"
                            class="w-full flex items-center gap-2 px-5 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor"
                                stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h6a2 2 0 012 2v1" />
                            </svg>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
            @endif
        </div>
    </nav>

    {{-- SIDEBAR --}}
    <aside x-data="{ open: false }"
           class="fixed top-0 left-0 z-40 w-64 h-screen bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700"
           aria-label="Sidebar">
        <div class="h-full px-3 py-4">
            @php
                $setting = App\Models\Setting::first();
            @endphp
            <a  class="flex items-center mb-5">
              <img src="{{ isset($setting->file_path) ? asset('storage/'.$setting->file_path) : asset('default-image.jpg') }}" class="h-6 mr-2" alt="Logo">
                <span class="text-xl font-semibold dark:text-white">{{ old('name',isset($setting->name) ? ucfirst($setting->name ): 'CRM') }}</span>
            </a>

            <ul class="space-y-2 font-medium">
                <li>
                    <a href="/dashboard" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">
                        Dashboard
                    </a>
                </li>
                <li>
                    <a href="/user-roles" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">
                        Users
                    </a>
                </li>
                <li>
                    <a href="{{ route('roles.index') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">
                        Roles
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">
                        Orders
                    </a>
                </li>
                <li>
                    <a href="{{ route('settings.index') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">
                        Settings
                    </a>
                </li>
            </ul>
        </div>
    </aside>

    {{-- MAIN CONTENT --}}
    <main class="sm:ml-64 mt-16">
            @if(session('success'))
                <div id="alert" class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
                <script>
                    setTimeout(() => document.getElementById('alert').style.display='none', 2000);
                </script>
            @endif
        <div class="p-4 border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700 min-h-screen">
            @yield('content')
        @if (Route::currentRouteName() === 'dashboard')
            <h1 class="text-2xl font-semibold mb-4 dark:text-white">Dashboard</h1>

            <div class="grid grid-cols-3 gap-4 mb-4">
                <div class="flex items-center justify-center h-24 rounded-sm bg-gray-50 dark:bg-gray-800">
                    <p class="text-gray-400">Widget 1</p>
                </div>
                <div class="flex items-center justify-center h-24 rounded-sm bg-gray-50 dark:bg-gray-800">
                    <p class="text-gray-400">Widget 2</p>
                </div>
                <div class="flex items-center justify-center h-24 rounded-sm bg-gray-50 dark:bg-gray-800">
                    <p class="text-gray-400">Widget 3</p>
                </div>
            </div>

            <div class="flex items-center justify-center h-48 mb-4 rounded-sm bg-gray-50 dark:bg-gray-800">
                <p class="text-gray-400">Main Section</p>
            </div>
        @endif
        </div>
    </main>

    {{-- DataTables JS --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    @yield('scripts')
    <script>
    $(document).ready(function() {
        $('#userDropdownBtn').on('click', function(e) {
            e.stopPropagation();
            $('#userDropdownMenu').toggleClass('hidden');
        });

        $(document).on('click', function() {
            $('#userDropdownMenu').addClass('hidden');
        });

        $('#userDropdownMenu').on('click', function(e) {
            e.stopPropagation();
        });
    });
    </script>
</body>
</html>
