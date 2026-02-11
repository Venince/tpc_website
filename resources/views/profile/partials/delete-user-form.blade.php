<section class="space-y-5">
    <header class="pb-1">
        <h2 class="text-lg font-semibold text-tpc-ink">
            {{ __('Delete Account') }}
        </h2>

        <p class="mt-1 text-sm text-tpc-ink/70">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </header>

    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="w-full sm:w-auto justify-center rounded-xl bg-red-600 px-5 py-3 text-sm font-semibold text-white
               shadow-sm hover:bg-red-700 focus:ring-2 focus:ring-red-200"
    >
        {{ __('Delete Account') }}
    </x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6 sm:p-7">
            @csrf
            @method('delete')

            <h2 class="text-lg font-semibold text-tpc-ink">
                {{ __('Are you sure you want to delete your account?') }}
            </h2>

            <p class="mt-2 text-sm text-tpc-ink/70">
                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
            </p>

            <div class="mt-5">
                <x-input-label for="password" value="{{ __('Password') }}" class="sr-only" />

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="mt-2 block w-full rounded-xl border border-tpc-primary/20 bg-white/80
                           focus:border-tpc-primary focus:ring-tpc-primary/25"
                    placeholder="{{ __('Password') }}"
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                <x-secondary-button
                    x-on:click="$dispatch('close')"
                    class="w-full sm:w-auto justify-center rounded-xl border border-tpc-primary/20 bg-white/80
                           px-5 py-3 text-sm font-semibold text-tpc-ink/80 hover:bg-white"
                >
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-danger-button
                    class="w-full sm:w-auto justify-center rounded-xl bg-red-600 px-5 py-3 text-sm font-semibold text-white
                           shadow-sm hover:bg-red-700"
                >
                    {{ __('Delete Account') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>
