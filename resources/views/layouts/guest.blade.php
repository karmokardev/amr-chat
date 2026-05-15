<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'amr_chat') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="relative min-h-screen overflow-x-hidden bg-gradient-to-br from-[#1A120F] via-[#2A1B16] to-[#120D0B] font-sans antialiased text-white">

    <!-- Wrapper -->
    <div class="relative flex flex-col min-h-screen overflow-hidden">

        <!-- Background Glow -->
        <div class="pointer-events-none absolute -left-24 -top-24 h-60 w-60 rounded-full bg-[#D97757]/20 blur-3xl sm:h-80 sm:w-80"></div>

        <div class="absolute rounded-full pointer-events-none -bottom-24 -right-24 h-60 w-60 bg-orange-400/10 blur-3xl sm:h-80 sm:w-80"></div>

        <!-- Main -->
        <main class="relative z-10 flex items-center justify-center flex-1 px-4 py-6 sm:px-6 lg:px-8">

            <!-- Card -->
            <div class="w-full">

                <!-- Form Container -->
                <div class="p-5 sm:p-8 lg:p-10">

                    {{ $slot }}

                </div>

                <!-- Footer -->
                <div class="mt-5 text-xs text-center text-gray-400 sm:text-sm">

                    © {{ date('Y') }}

                    <span class="font-semibold text-[#D97757]">
                        amr_chat
                    </span>

                    . All rights reserved.

                </div>

            </div>

        </main>

    </div>

</body>
</html>