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
                                    d="M21.75 9v.906a2.25 2.25 0 01-.665 1.591l-7.5 7.5a2.25 2.25 0 01-3.182 0l-7.5-7.5A2.25 2.25 0 012.25 9.906V9m19.5 0V6.75A2.25 2.25 0 0019.5 4.5h-15A2.25 2.25 0 002.25 6.75V9"
                                />
                            </svg>

                        </div>

                        <h1 class="text-2xl font-bold tracking-wide text-white">
                            Verify Email
                        </h1>

                        <p class="mt-3 text-sm leading-relaxed text-gray-400">
                            Please verify your email address before accessing your account.
                        </p>

                    </div>

                    <!-- Description -->
                    <div class="mb-5 text-sm leading-relaxed text-gray-300">

                        {{ __('Thanks for signing up! Before getting started, please verify your email address by clicking on the link we just emailed to you. If you didn\'t receive the email, we’ll gladly send another.') }}

                    </div>

                    <!-- Success Message -->
                    @if (session('status') == 'verification-link-sent')

                        <div class="p-4 mb-5 text-sm text-green-400 border rounded-2xl border-green-500/20 bg-green-500/10">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </div>

                    @endif

                    <!-- Actions -->
                    <div class="space-y-4">

                        <!-- Resend -->
                        <form
                            method="POST"
                            action="{{ route('verification.send') }}"
                        >
                            @csrf

                            <button
                                type="submit"
                                class="w-full py-3 text-sm font-semibold text-white transition-all duration-300 rounded-2xl bg-[#D97757] hover:bg-[#ca6d4d] active:scale-[0.99] shadow-lg shadow-[#D97757]/20"
                            >
                                Resend Verification Email
                            </button>

                        </form>

                        <!-- Logout -->
                        <form
                            method="POST"
                            action="{{ route('logout') }}"
                        >
                            @csrf

                            <button
                                type="submit"
                                class="w-full py-3 text-sm font-medium text-gray-300 transition-all duration-300 border rounded-2xl border-white/10 bg-white/5 hover:bg-white/10 hover:text-white"
                            >
                                Log Out
                            </button>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

</x-guest-layout>