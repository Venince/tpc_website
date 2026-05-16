<x-guest-layout>

    <x-auth-session-status class="mb-5" :status="session('status')" />

    {{-- Header --}}
    <div class="mb-6 border-b border-tpc-primary/10 pb-5">
        <div class="flex items-center gap-3 mb-1">
            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-tpc-primary/8 border border-tpc-primary/15">
                <svg class="h-4 w-4 text-tpc-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/>
                </svg>
            </div>
            <h1 class="text-xl font-semibold text-tpc-ink">Reset password</h1>
        </div>
        <p class="mt-1 text-sm text-tpc-ink/55 leading-relaxed">
            Enter your email and we'll send you a link to reset your password.
        </p>
    </div>

    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
        @csrf

        {{-- Email --}}
        <div>
            <x-input-label for="email" :value="__('Email address')" />
            <div class="relative mt-1">
                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </span>
                <x-text-input
                    id="email"
                    class="block w-full rounded-xl border-gray-200 pl-9 focus:border-tpc-primary focus:ring-tpc-primary/20"
                    type="email"
                    name="email"
                    :value="old('email')"
                    placeholder="you@tpc.edu.ph"
                    required
                    autofocus
                    autocomplete="email"
                />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
        </div>

        {{-- Submit --}}
        <div class="pt-1">
            <button type="submit"
                class="flex w-full items-center justify-center gap-2 rounded-xl bg-tpc-primary px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-tpc-secondary focus:outline-none focus:ring-2 focus:ring-tpc-primary/40 focus:ring-offset-2 active:scale-[0.99]">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                {{ __('Email Password Reset Link') }}
            </button>
        </div>

    </form>

    {{-- Back to login --}}
    <div class="mt-6 text-center">
        <a href="{{ route('login') }}"
           class="inline-flex items-center gap-1 text-sm font-medium text-tpc-primary hover:text-tpc-secondary transition">
            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Sign in
        </a>
    </div>

</x-guest-layout>
