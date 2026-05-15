<x-guest-layout>

    <div class="fixed inset-0 overflow-hidden bg-gradient-to-br from-[#140E0C] via-[#221713] to-[#0F0A08]">

        <!-- Glow Effects -->
        <div class="absolute top-0 right-0 w-72 h-72 rounded-full bg-[#D97757]/10 blur-3xl"></div>

        <div class="absolute bottom-0 left-0 rounded-full w-60 h-60 bg-orange-400/10 blur-3xl"></div>

        <!-- Center -->
        <div class="relative flex items-center justify-center w-full h-full px-4">

            <!-- Card -->
            <div class="w-full max-w-md border border-white/10 rounded-3xl bg-[#1B1411]/95 backdrop-blur-2xl shadow-[0_10px_50px_rgba(0,0,0,0.45)] overflow-hidden">

                <!-- Top Line -->
                <div class="h-1 bg-gradient-to-r from-[#D97757] via-orange-400 to-[#D97757]"></div>

                <!-- Content -->
                <div class="p-6 sm:p-7">

                    <!-- Header -->
                    <div class="text-center mb-7">

                        <!-- Icon -->
                        <div class="flex items-center justify-center w-16 h-16 mx-auto mb-5 border rounded-2xl bg-[#D97757]/10 border-[#D97757]/20">

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.8"
                                stroke="currentColor"
                                class="w-8 h-8 text-[#D97757]"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M21.75 9v.906a2.25 2.25 0 01-.665 1.591l-7.5 7.5a2.25 2.25 0 01-3.182 0l-7.5-7.5A2.25 2.25 0 012.25 9.906V9m19.5 0V6.75A2.25 2.25 0 0019.5 4.5h-15A2.25 2.25 0 002.25 6.75V9m19.5 0l-8.204 5.47a2.25 2.25 0 01-2.492 0L2.25 9"
                                />
                            </svg>

                        </div>

                        <h1 class="text-2xl font-bold tracking-wide text-white">
                            Forgot Password?
                        </h1>

                        <p class="mt-3 text-sm leading-relaxed text-gray-400">
                            Enter your email and we’ll send you a password reset link instantly.
                        </p>

                    </div>

                    <!-- Session Status -->
                    <x-auth-session-status
                        class="mb-4 text-sm text-green-400"
                        :status="session('status')"
                    />

                    <!-- Form -->
                    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
                        @csrf

                        <!-- Email -->
                        <div>

                            <label class="block mb-2 text-sm font-medium text-gray-300">
                                Email Address
                            </label>

                            <input
                                id="email"
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                autofocus
                                placeholder="Enter your email"
                                class="w-full px-4 py-3 text-sm text-white transition-all duration-300 border outline-none rounded-2xl bg-white/5 border-white/10 placeholder:text-gray-500 focus:border-[#D97757] focus:bg-white/[0.06]"
                            >

                            <x-input-error
                                :messages="$errors->get('email')"
                                class="mt-2 text-sm text-red-400"
                            />

                        </div>

                        <!-- Button -->
                        <button
                            type="submit"
                            class="w-full py-3 text-sm font-semibold text-white transition-all duration-300 rounded-2xl bg-[#D97757] hover:bg-[#ca6d4d] active:scale-[0.99] shadow-lg shadow-[#D97757]/20"
                        >
                            Send Reset Link
                        </button>

                    </form>

                    <!-- Footer -->
                    <div class="mt-6 text-sm text-center text-gray-400">

                        Remember your password?

                        <a
                            href="{{ route('login') }}"
                            class="ml-1 font-semibold text-[#D97757] hover:text-[#f09a7c] transition"
                        >
                            Back to Login
                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</x-guest-layout>