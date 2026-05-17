<!DOCTYPE html>
<html lang="en"
    x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }"
    x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))"
    :class="darkMode ? 'dark' : ''">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user-id" content="{{ Auth::id() }}">
    <meta name="user-name" content="{{ Auth::user()->name }}">
    <title>{{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex flex-col h-screen text-gray-900 bg-white dark:bg-gray-900 dark:text-gray-100">

    {{-- Navbar --}}
    <nav class="flex items-center justify-between px-4 border-b border-gray-200 h-14 dark:border-gray-700 shrink-0">

        <span class="font-bold text-[#D97757] text-lg">AMR Chat</span>

        <div class="flex items-center gap-3">

            {{-- Dark mode toggle --}}
            <button @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode)"
                class="p-2 transition rounded-full hover:bg-gray-100 dark:hover:bg-gray-800">
                <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                </svg>
                <svg x-show="darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707.707M6.343 6.343l-.707.707"/>
                </svg>
            </button>

            {{-- User --}}
            <span class="text-sm font-medium">{{ Auth::user()->name }}</span>

            {{-- Logout --}}
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="text-sm text-red-500 transition hover:text-red-600">
                    Logout
                </button>
            </form>

        </div>
    </nav>

    {{-- Main --}}
    <main class="flex flex-1 overflow-hidden">
        @yield('content')
    </main>
@stack('scripts')
</body>
</html>