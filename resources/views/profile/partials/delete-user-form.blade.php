{{-- resources/views/profile/partials/delete-user-form.blade.php --}}

{{-- Section header --}}
<div class="px-5 py-4 border-b border-red-100 flex items-center gap-3">
    <div class="h-8 w-8 rounded-lg bg-red-50 flex items-center justify-center shrink-0">
        <svg class="h-4 w-4 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
        </svg>
    </div>
    <div>
        <h2 class="text-sm font-bold text-red-600">{{ __('Delete Account') }}</h2>
        <p class="text-xs text-gray-400 mt-0.5">{{ __('Permanently remove your account and all data.') }}</p>
    </div>
</div>

<div class="p-5" x-data="{ confirm: false }">

    {{-- Description + trigger --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <p class="text-sm text-gray-500 max-w-sm">
            {{ __('Once deleted, all your data will be permanently removed and cannot be recovered.') }}
        </p>
        <button type="button" @click="confirm = true"
                class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl border border-red-200 text-sm font-bold text-red-600 hover:bg-red-50 active:scale-95 transition-all shrink-0">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>
            {{ __('Delete Account') }}
        </button>
    </div>

    {{-- Inline confirm panel --}}
    <div x-show="confirm" x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         class="mt-5 p-4 bg-red-50 rounded-xl border border-red-200">

        <p class="text-sm font-semibold text-red-700 mb-1">
            {{ __('Are you sure you want to delete your account?') }}
        </p>
        <p class="text-xs text-red-500 mb-4">
            {{ __('Enter your password to confirm. This action cannot be undone.') }}
        </p>

        <form method="post" action="{{ route('profile.destroy') }}"
              x-data="{ showPass: false }">
            @csrf
            @method('delete')

            <div class="mb-4">
                <label for="del-password"
                       class="block text-xs font-bold text-gray-600 mb-1.5 uppercase tracking-wide">
                    {{ __('Password') }}
                </label>
                <div class="relative">
                    <input id="del-password" name="password"
                           :type="showPass ? 'text' : 'password'"
                           placeholder="{{ __('Enter your password') }}"
                           class="w-full bg-white border border-red-200 rounded-xl px-4 py-2.5 pr-11 text-sm text-gray-800
                                  placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-red-200 focus:border-red-400 transition">
                    <button type="button" @click="showPass = !showPass"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition">
                        <svg x-show="!showPass" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <svg x-show="showPass" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/>
                        </svg>
                    </button>
                </div>
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-1.5" />
            </div>

            <div class="flex items-center gap-3">
                <button type="submit"
                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-red-600 text-white text-sm font-bold hover:bg-red-700 active:scale-95 transition-all">
                    {{ __('Yes, delete my account') }}
                </button>
                <button type="button" @click="confirm = false"
                        class="px-4 py-2 rounded-xl border border-gray-200 bg-white text-sm font-bold text-gray-600 hover:bg-gray-50 active:scale-95 transition-all">
                    {{ __('Cancel') }}
                </button>
            </div>
        </form>
    </div>
</div>
