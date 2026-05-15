<x-guest-layout>

    <div class="fixed inset-0 overflow-hidden bg-gradient-to-br from-[#1A120F] via-[#241713] to-[#120D0B]">

        <!-- Glow -->
        <div class="absolute top-0 right-0 w-72 h-72 bg-[#D97757]/10 blur-3xl rounded-full"></div>

        <div class="absolute bottom-0 left-0 rounded-full w-60 h-60 bg-orange-400/10 blur-3xl"></div>

        <!-- Center -->
        <div class="relative flex items-center justify-center w-full h-full px-4">

            <!-- Card -->
            <div class="w-full max-w-md overflow-hidden border shadow-2xl bg-[#1E1512]/95 border-white/10 backdrop-blur-2xl rounded-3xl">

                <!-- Top Accent -->
                <div class="h-1 bg-gradient-to-r from-[#D97757] via-orange-400 to-[#D97757]"></div>

                <!-- Content -->
                <div class="p-5 sm:p-6">

                    <!-- Header -->
                    <div class="mb-6 text-center">

                        <div class="inline-flex items-center justify-center h-14 px-5 mb-4 rounded-2xl bg-[#D97757]/10 border border-[#D97757]/20">

                            <span class="text-2xl font-black text-white">
                                Amr<span class="text-[#D97757]">Chat</span>
                            </span>

                        </div>

                        <h1 class="text-2xl font-bold text-white">
                            Create Account
                        </h1>

                        <p class="mt-2 text-sm text-gray-400">
                            Start chatting instantly.
                        </p>

                    </div>

                    <!-- Form -->
                    <form method="POST" action="{{ route('register') }}" class="space-y-3">
                        @csrf

                        <!-- Name -->
                        <div>

                            <input
                                type="text"
                                name="name"
                                placeholder="Full Name"
                                value="{{ old('name') }}"
                                required
                                class="w-full px-4 py-3 text-sm text-white border rounded-2xl bg-white/5 border-white/10 placeholder:text-gray-500 focus:border-[#D97757] focus:ring-0 outline-none"
                            >

                        </div>

                        <!-- Username -->
                        <div>

                            <input
                                type="text"
                                name="username"
                                placeholder="Username"
                                value="{{ old('username') }}"
                                required
                                class="w-full px-4 py-3 text-sm text-white border rounded-2xl bg-white/5 border-white/10 placeholder:text-gray-500 focus:border-[#D97757] focus:ring-0 outline-none"
                            >

                        </div>

                        <!-- Email -->
                        <div>

                            <input
                                type="email"
                                name="email"
                                placeholder="Email Address"
                                value="{{ old('email') }}"
                                required
                                class="w-full px-4 py-3 text-sm text-white border rounded-2xl bg-white/5 border-white/10 placeholder:text-gray-500 focus:border-[#D97757] focus:ring-0 outline-none"
                            >

                        </div>

                        <!-- Password -->
                        <div>

                            <input
                                type="password"
                                name="password"
                                placeholder="Password"
                                required
                                class="w-full px-4 py-3 text-sm text-white border rounded-2xl bg-white/5 border-white/10 placeholder:text-gray-500 focus:border-[#D97757] focus:ring-0 outline-none"
                            >

                        </div>

                        <!-- Confirm Password -->
                        <div>

                            <input
                                type="password"
                                name="password_confirmation"
                                placeholder="Confirm Password"
                                required
                                class="w-full px-4 py-3 text-sm text-white border rounded-2xl bg-white/5 border-white/10 placeholder:text-gray-500 focus:border-[#D97757] focus:ring-0 outline-none"
                            >

                        </div>

                        <!-- Button -->
                        <button
                            type="submit"
                            class="w-full py-3 mt-2 text-sm font-semibold text-white transition-all rounded-2xl bg-[#D97757] hover:bg-[#c96a4b]"
                        >
                            Create Account
                        </button>

                    </form>

                    <!-- Footer -->
                    <div class="mt-5 text-sm text-center text-gray-400">

                        Already have an account?

                        <a
                            href="{{ route('login') }}"
                            class="ml-1 font-semibold text-[#D97757]"
                        >
                            Login
                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</x-guest-layout>