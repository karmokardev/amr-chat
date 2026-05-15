<x-guest-layout>

    <div class="flex items-center justify-center px-4">

        <!-- Login Box -->
        <div class="relative w-full max-w-md">

            <!-- Glow -->
            <div class="absolute -top-10 -left-10 w-40 h-40 bg-[#D97757]/20 blur-3xl rounded-full"></div>

            <!-- Card -->
            <div class="relative bg-[#201613]/90 border border-white/10 backdrop-blur-2xl rounded-3xl p-8 shadow-2xl overflow-hidden">

                <!-- Top Line -->
                <div class="absolute top-0 left-0 w-full h-1 bg-[#D97757]"></div>

                <!-- Header -->
                <div class="mb-8 text-center">

                    <div class="inline-flex items-center justify-center px-5 h-16 rounded-2xl bg-[#D97757]/15 border border-[#D97757]/20 mb-5 shadow-lg shadow-[#D97757]/10 backdrop-blur-xl">

                        <span class="text-2xl font-extrabold tracking-wide text-white">
                            Amr<span class="text-[#D97757]">Chat</span>
                        </span>

                    </div>

                    <h1 class="text-3xl font-extrabold tracking-wide text-white">
                        Welcome Back
                    </h1>

                    <p class="mt-2 text-sm text-gray-400">
                        Login to continue your conversation.
                    </p>

                </div>

                <!-- Status -->
                <x-auth-session-status
                    class="mb-4 text-sm text-green-400"
                    :status="session('status')"
                />

                <!-- Form -->
                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    <!-- Email -->
                    <div>

                        <label class="block mb-2 text-sm text-gray-300">
                            Email Address
                        </label>

                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            autocomplete="username"
                            placeholder="Enter your email"
                            class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder:text-gray-500 focus:border-[#D97757] focus:ring-0 outline-none transition"
                        >

                        <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-400" />

                    </div>

                    <!-- Password -->
                    <div>

                        <div class="flex items-center justify-between mb-2">

                            <label class="text-sm text-gray-300">
                                Password
                            </label>

                            

                        </div>

                        <input
                            id="password"
                            type="password"
                            name="password"
                            required
                            autocomplete="current-password"
                            placeholder="Enter your password"
                            class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder:text-gray-500 focus:border-[#D97757] focus:ring-0 outline-none transition"
                        >

                        <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-red-400" />

                    </div>

                    <!-- Remember -->
                    <label class="flex items-center justify-between gap-3 cursor-pointer justify justi j">
                        <div class="flex items-center gap-2">
                            <input
                                type="checkbox"
                                name="remember"
                                class="rounded border-white/20 bg-white/10 text-[#D97757] focus:ring-[#D97757]"
                            >

                            <span class="text-sm text-gray-400">
                                Remember me
                            </span>
                        </div>
                        @if (Route::has('password.request'))
                            <a
                                href="{{ route('password.request') }}"
                                class="text-xs text-[#D97757] hover:text-[#e98c6d] transition"
                            >
                                Forgot?
                            </a>
                        @endif
                    </label>

                    <!-- Button -->
                    <button
                        type="submit"
                        class="w-full py-3 rounded-xl bg-[#D97757] hover:bg-[#c96a4b] text-white font-semibold transition-all duration-300 shadow-lg shadow-[#D97757]/20 hover:translate-y-[-1px]"
                    >
                        Login
                    </button>

                </form>

                <!-- Footer -->
                <div class="mt-6 text-sm text-center text-gray-400">

                    Don't have an account?

                    <a
                        href="{{ route('register') }}"
                        class="text-[#D97757] font-semibold hover:text-[#e98c6d] transition"
                    >
                        Create account
                    </a>

                </div>

            </div>

        </div>

    </div>

</x-guest-layout>