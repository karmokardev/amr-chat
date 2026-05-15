<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Realtime Chat App</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen flex flex-col text-white bg-gradient-to-br from-[#1A120F] via-[#2A1B16] to-[#120D0B]">

    <!-- Navbar -->
    <header class="flex items-center justify-between w-full px-8 py-5 border-b border-white/10 backdrop-blur-lg">
        <h1 class="text-2xl font-bold tracking-wide">
            amr_<span class="text-[#D97757]">chat</span>
        </h1>

        @if (Route::has('login'))
            <div class="space-x-4">
                @auth
                    <a href="{{ url('/dashboard') }}"
                       class="px-5 py-2 transition border rounded-xl border-white/20 hover:bg-white/10">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}"
                       class="px-5 py-2 transition border rounded-xl border-white/20 hover:bg-white/10">
                        Login
                    </a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}"
                           class="px-5 py-2 rounded-xl bg-[#D97757] hover:bg-[#c96a4b] transition shadow-lg shadow-[#D97757]/30">
                            Register
                        </a>
                    @endif
                @endauth
            </div>
        @endif
    </header>

    <!-- Main Content -->
    <main class="flex-1">

        <!-- Hero Section -->
        <section class="flex flex-col items-center justify-between gap-16 px-10 py-20 lg:flex-row lg:px-24">

            <!-- Left -->
            <div class="max-w-2xl">

                <div class="inline-block px-4 py-2 mb-6 text-sm rounded-full bg-[#D97757]/20 text-[#D97757] border border-[#D97757]/20">
                    Modern Realtime Messaging
                </div>

                <h2 class="text-5xl font-extrabold leading-tight lg:text-7xl">
                    Connect Faster <br>
                    With <span class="text-[#D97757]">amr_chat</span>
                </h2>

                <p class="mt-8 text-lg leading-relaxed text-gray-300">
                    Experience blazing fast realtime messaging with a clean
                    modern interface. Chat instantly with friends, teams and communities.
                </p>

                <!-- Buttons -->
                <div class="flex gap-5 mt-10">

                    <a href="{{ route('register') }}"
                       class="px-8 py-4 rounded-2xl bg-[#D97757] hover:bg-[#c96a4b] transition text-lg font-semibold shadow-2xl shadow-[#D97757]/30">
                        Start Chatting
                    </a>

                    <a href="{{ route('login') }}"
                       class="px-8 py-4 text-lg transition border rounded-2xl border-white/20 hover:bg-white/10">
                        Login
                    </a>

                </div>

                <!-- Stats -->
                <div class="flex gap-10 mt-16">

                    <div>
                        <h3 class="text-3xl font-bold text-[#D97757]">10K+</h3>
                        <p class="text-gray-400">Messages Daily</p>
                    </div>

                    <div>
                        <h3 class="text-3xl font-bold text-[#D97757]">99.9%</h3>
                        <p class="text-gray-400">Secure Chat</p>
                    </div>

                    <div>
                        <h3 class="text-3xl font-bold text-[#D97757]">24/7</h3>
                        <p class="text-gray-400">Online Support</p>
                    </div>

                </div>

            </div>

            <!-- Right Chat Card -->
            <div class="relative">

                <div class="w-[360px] rounded-3xl border border-white/10 bg-white/10 backdrop-blur-xl p-6 shadow-2xl">

                    <!-- User -->
                    <div class="flex items-center gap-3 mb-6">

                        <div class="w-12 h-12 rounded-full bg-[#D97757] flex items-center justify-center font-bold">
                            H
                        </div>

                        <div>
                            <h4 class="font-semibold">Hridoy Karmokar</h4>
                            <p class="text-sm text-green-400">Online</p>
                        </div>

                    </div>

                    <!-- Messages -->
                    <div class="space-y-4">

                        <div class="bg-[#D97757] p-4 rounded-2xl rounded-bl-none max-w-[80%]">
                            Hey 👋 Welcome to amr_chat!
                        </div>

                        <div class="bg-white/10 p-4 rounded-2xl rounded-br-none max-w-[80%] ml-auto">
                            Hi! Thanks for having me here. This looks amazing!
                        </div>

                        <div class="bg-[#D97757] p-4 rounded-2xl rounded-bl-none max-w-[80%]">
                            Feel free to explore and let me know if you have any questions.
                        </div>

                    </div>

                    <!-- Input -->
                    <div class="flex items-center gap-3 mt-6">

                        <input
                            type="text"
                            placeholder="Type a message..."
                            class="flex-1 bg-white/10 border border-white/10 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-[#D97757] transition"
                        >

                        <button
                            class="bg-[#D97757] hover:bg-[#c96a4b] transition px-4 py-2.5 rounded-xl shadow-lg shadow-[#D97757]/20"
                        >
                            ➤
                        </button>

                    </div>

                </div>

                <!-- Glow -->
                <div class="absolute -top-10 -left-10 w-40 h-40 bg-[#D97757] rounded-full blur-3xl opacity-30"></div>

                <div class="absolute w-40 h-40 bg-orange-400 rounded-full -bottom-10 -right-10 blur-3xl opacity-20"></div>

            </div>

        </section>

    </main>

    <!-- Footer -->
    <footer class="py-6 mt-auto text-center text-gray-400 border-t border-white/10">
        © {{ date('Y') }}
        <span class="text-[#D97757] font-semibold">amr_chat</span>.
        All rights reserved by
        <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-[#D97757]/15 border border-[#D97757]/30 text-[#D97757] font-semibold tracking-wide shadow-lg shadow-[#D97757]/10 backdrop-blur-md hover:scale-105 transition duration-300">
    ✦ karmokardev
</span>
    </footer>

</body>
</html>