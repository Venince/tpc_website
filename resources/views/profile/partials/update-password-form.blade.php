<section>
    <header class="pb-2">
        <h2 class="text-lg font-semibold text-tpc-ink">
            {{ __('Update Password') }}
        </h2>

        <p class="mt-1 text-sm text-tpc-ink/70">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-5">
        @csrf
        @method('put')

        <div>
            <x-input-label for="update_password_current_password" :value="__('Current Password')" class="text-tpc-ink/80 font-medium" />
            <x-text-input
                id="update_password_current_password"
                name="current_password"
                type="password"
                class="mt-2 block w-full rounded-xl border border-tpc-primary/20 bg-white/80
                       focus:border-tpc-primary focus:ring-tpc-primary/25"
                autocomplete="current-password"
            />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password" :value="__('New Password')" class="text-tpc-ink/80 font-medium" />
            <x-text-input
                id="update_password_password"
                name="password"
                type="password"
                class="mt-2 block w-full rounded-xl border border-tpc-primary/20 bg-white/80
                       focus:border-tpc-primary focus:ring-tpc-primary/25"
                autocomplete="new-password"
            />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password_confirmation" :value="__('Confirm Password')" class="text-tpc-ink/80 font-medium" />
            <x-text-input
                id="update_password_password_confirmation"
                name="password_confirmation"
                type="password"
                class="mt-2 block w-full rounded-xl border border-tpc-primary/20 bg-white/80
                       focus:border-tpc-primary focus:ring-tpc-primary/25"
                autocomplete="new-password"
            />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex flex-col sm:flex-row sm:items-center gap-3">
            <x-primary-button
                class="w-full sm:w-auto justify-center rounded-xl bg-tpc-primary px-5 py-3 text-sm font-semibold text-white
                       shadow-sm hover:bg-tpc-secondary focus:ring-2 focus:ring-tpc-primary/25"
            >
                {{ __('Save') }}
            </x-primary-button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-tpc-ink/60"
                >
                    {{ __('Saved.') }}
                </p>
            @endif
        </div>
    </form>
</section>
