<x-guest-layout>
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-tpc-ink">Sign in</h1>
        <p class="mt-1 text-sm text-tpc-ink/60">
            Use your admin account to manage the website.
        </p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    @if ($errors->any())
        <div class="mb-4 rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-700">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input
                id="email"
                class="mt-1 block w-full rounded-lg border-gray-300 focus:border-tpc-primary focus:ring-tpc-primary/30"
                type="email"
                name="email"
                :value="old('email')"
                required
                autofocus
                autocomplete="username"
            />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input
                id="password"
                class="mt-1 block w-full rounded-lg border-gray-300 focus:border-tpc-primary focus:ring-tpc-primary/30"
                type="password"
                name="password"
                required
                autocomplete="current-password"
            />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center gap-2">
                <input
                    id="remember_me"
                    type="checkbox"
                    class="rounded border-gray-300 text-tpc-primary focus:ring-tpc-primary/30"
                    name="remember"
                >
                <span class="text-sm text-tpc-ink/70">{{ __('Remember me') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm font-medium text-tpc-primary hover:text-tpc-secondary"
                   href="{{ route('password.request') }}">
                    {{ __('Forgot password?') }}
                </a>
            @endif
        </div>

        <div class="pt-2">
            <x-primary-button class="w-full justify-center bg-tpc-primary hover:bg-tpc-secondary focus:ring-tpc-primary/40">
                {{ __('Log in') }}
            </x-primary-button>
        </div>

    </form>
</x-guest-layout>
