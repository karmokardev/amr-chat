<x-guest-layout>

    <div class="fixed inset-0 overflow-hidden bg-gradient-to-br from-[#140E0C] via-[#221713] to-[#0F0A08]">

        <!-- Glow Effects -->
        <div class="absolute top-0 right-0 w-72 h-72 rounded-full bg-[#D97757]/10 blur-3xl"></div>

        <div class="absolute bottom-0 left-0 rounded-full w-60 h-60 bg-orange-400/10 blur-3xl"></div>

        <!-- Center -->
        <div class="relative flex items-center justify-center w-full h-full px-4">

            <!-- Card -->
            <div class="w-full max-w-md overflow-hidden border border-white/10 rounded-3xl bg-[#1B1411]/95 backdrop-blur-2xl shadow-[0_10px_50px_rgba(0,0,0,0.45)]">

                <!-- Top Accent -->
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
                                    d="M12 15v2.25m0 0a2.25 2.25 0 002.25-2.25V9.75A2.25 2.25 0 0012 7.5m0 9.75A2.25 2.25 0 019.75 15V9.75A2.25 2.25 0 0112 7.5m0 0V6a3 3 0 116 0v1.5"
                                />
                            </svg>

                        </div>

                        <h1 class="text-2xl font-bold tracking-wide text-white">
                            Reset Password
                        </h1>

                        <p class="mt-3 text-sm leading-relaxed text-gray-400">
                            Create a strong new password to secure your account.
                        </p>

                    </div>

                    <!-- Form -->
                    <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
                        @csrf

                        <!-- Token -->
                        <input
                            type="hidden"
                            name="token"
                            value="{{ $request->route('token') }}"
                        >

                        <!-- Email -->
                        <div>

                            <label class="block mb-2 text-sm font-medium text-gray-300">
                                Email Address
                            </label>

                            <input
                                id="email"
                                type="email"
                                name="email"
                                value="{{ old('email', $request->email) }}"
                                required
                                autofocus
                                autocomplete="username"
                                placeholder="Enter your email"
                                class="w-full px-4 py-3 text-sm text-white transition-all duration-300 border outline-none rounded-2xl bg-white/5 border-white/10 placeholder:text-gray-500 focus:border-[#D97757] focus:bg-white/[0.06]"
                            >

                            <x-input-error
                                :messages="$errors->get('email')"
                                class="mt-2 text-sm text-red-400"
                            />

                        </div>

                        <!-- Password -->
                        <div>

                            <label class="block mb-2 text-sm font-medium text-gray-300">
                                New Password
                            </label>

                            <input
                                id="password"
                                type="password"
                                name="password"
                                required
                                autocomplete="new-password"
                                placeholder="Enter new password"
                                class="w-full px-4 py-3 text-sm text-white transition-all duration-300 border outline-none rounded-2xl bg-white/5 border-white/10 placeholder:text-gray-500 focus:border-[#D97757] focus:bg-white/[0.06]"
                            >

                            <x-input-error
                                :messages="$errors->get('password')"
                                class="mt-2 text-sm text-red-400"
                            />

                        </div>

                        <!-- Confirm Password -->
                        <div>

                            <label class="block mb-2 text-sm font-medium text-gray-300">
                                Confirm Password
                            </label>

                            <input
                                id="password_confirmation"
                                type="password"
                                name="password_confirmation"
                                required
                                autocomplete="new-password"
                                placeholder="Confirm password"
                                class="w-full px-4 py-3 text-sm text-white transition-all duration-300 border outline-none rounded-2xl bg-white/5 border-white/10 placeholder:text-gray-500 focus:border-[#D97757] focus:bg-white/[0.06]"
                            >

                            <x-input-error
                                :messages="$errors->get('password_confirmation')"
                                class="mt-2 text-sm text-red-400"
                            />

                        </div>

                        <!-- Button -->
                        <button
                            type="submit"
                            class="w-full py-3 text-sm font-semibold text-white transition-all duration-300 rounded-2xl bg-[#D97757] hover:bg-[#ca6d4d] active:scale-[0.99] shadow-lg shadow-[#D97757]/20"
                        >
                            Reset Password
                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

</x-guest-layout>