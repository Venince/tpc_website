{{-- resources/views/profile/partials/update-profile-information-form.blade.php --}}

{{-- Section header --}}
<div class="px-5 py-4 border-b border-gray-100 flex items-center gap-3">
    <div class="h-8 w-8 rounded-lg bg-tpc-primary/10 flex items-center justify-center shrink-0">
        <svg class="h-4 w-4 text-tpc-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
        </svg>
    </div>
    <div>
        <h2 class="text-sm font-bold text-gray-800">{{ __('Profile Information') }}</h2>
        <p class="text-xs text-gray-400 mt-0.5">{{ __("Update your name and email address.") }}</p>
    </div>
</div>

<form id="send-verification" method="post" action="{{ route('verification.send') }}">
    @csrf
</form>

<form method="post" action="{{ route('profile.update') }}" class="p-5 space-y-4">
    @csrf
    @method('patch')

    {{-- Name --}}
    <div>
        <x-input-label for="name" :value="__('Name')"
            class="block text-xs font-bold text-gray-600 mb-1.5 uppercase tracking-wide" />
        <x-text-input
            id="name" name="name" type="text"
            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-800
                   placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-tpc-primary/30 focus:border-tpc-primary transition"
            :value="old('name', $user->name)"
            required autofocus autocomplete="name"
        />
        <x-input-error class="mt-1.5" :messages="$errors->get('name')" />
    </div>

    {{-- Email --}}
    <div>
        <x-input-label for="email" :value="__('Email')"
            class="block text-xs font-bold text-gray-600 mb-1.5 uppercase tracking-wide" />
        <div class="relative">
            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>
                </svg>
            </span>
            <x-text-input
                id="email" name="email" type="email"
                class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-10 pr-4 py-2.5 text-sm text-gray-800
                       placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-tpc-primary/30 focus:border-tpc-primary transition"
                :value="old('email', $user->email)"
                required autocomplete="username"
            />
        </div>
        <x-input-error class="mt-1.5" :messages="$errors->get('email')" />

        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <div class="mt-3 flex items-start gap-3 p-3.5 bg-amber-50 rounded-xl border border-amber-100">
                <svg class="h-4 w-4 text-amber-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                </svg>
                <div>
                    <p class="text-xs font-semibold text-amber-700">{{ __('Email not verified') }}</p>
                    <p class="text-xs text-amber-600 mt-0.5">{{ __('Your email address is unverified.') }}</p>
                    <button form="send-verification"
                            class="mt-2 text-xs font-bold text-tpc-primary hover:text-tpc-secondary transition underline underline-offset-2">
                        {{ __('Re-send verification email') }}
                    </button>
                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 text-xs font-semibold text-green-600">
                            {{ __('Verification link sent!') }}
                        </p>
                    @endif
                </div>
            </div>
        @endif
    </div>

    {{-- Actions --}}
    <div class="flex flex-col-reverse sm:flex-row sm:items-center gap-3 pt-1">
        @if (session('status') === 'profile-updated')
            <p x-data="{ show: true }" x-show="show" x-transition
               x-init="setTimeout(() => show = false, 2500)"
               class="text-xs font-semibold text-green-600 flex items-center gap-1.5">
                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                </svg>
                {{ __('Changes saved!') }}
            </p>
        @endif
        <button type="submit"
                class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-7 py-2.5 rounded-xl bg-tpc-primary text-white text-sm font-bold hover:bg-tpc-secondary active:scale-95 transition-all shadow-sm">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
            </svg>
            {{ __('Save Changes') }}
        </button>
    </div>
</form>
